<?php
session_start();
include("../includes/db.php");

// Send the user back to the login page with an error message (no alert() popups)
function admin_login_fail($message, $username = '')
{
    $_SESSION['admin_flash_error']    = $message;
    $_SESSION['admin_flash_username'] = $username;
    header("Location: admin-login.php");
    exit();
}

// Only accept real form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin-login.php");
    exit();
}

// Basic brute-force slowdown: 5 failed tries -> 60 second lockout (per browser session)
$maxAttempts = 5;
$lockSeconds = 60;

if (($_SESSION['admin_fail_count'] ?? 0) >= $maxAttempts) {
    $wait = ($_SESSION['admin_locked_until'] ?? 0) - time();
    if ($wait > 0) {
        admin_login_fail("Too many attempts. Try again in {$wait} seconds.");
    }
    $_SESSION['admin_fail_count'] = 0;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';   // don't trim passwords

// Look the admin up by username ONLY, then verify the password in PHP
$stmt = mysqli_prepare($conn, "SELECT id, username, password FROM admin WHERE username=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$valid = false;

if ($admin === null) {
    // Unknown username: still do a hash check so response time doesn't reveal it
    password_verify($password, password_hash('not-a-real-password', PASSWORD_DEFAULT));
} else {
    $stored = (string)$admin['password'];

    if (password_get_info($stored)['algo'] !== null) {
        // Normal case: the stored value is a proper password hash
        $valid = password_verify($password, $stored);
    } else {
        // ---- TEMPORARY MIGRATION BLOCK ---------------------------------------
        // Old rows still hold the plain-text password. Accept it once, then
        // replace it with a hash. After you've logged in once you can delete
        // this whole else-branch.
        $valid = hash_equals($stored, $password);
        if ($valid) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $up = mysqli_prepare($conn, "UPDATE admin SET password=? WHERE id=?");
            mysqli_stmt_bind_param($up, "si", $newHash, $admin['id']);
            mysqli_stmt_execute($up);
            mysqli_stmt_close($up);
        }
        // ----------------------------------------------------------------------
    }
}

if ($valid) {
    session_regenerate_id(true);          // prevents session fixation
    $_SESSION['admin'] = $admin['username'];
    unset($_SESSION['admin_fail_count'], $_SESSION['admin_locked_until']);

    header("Location: admin-dashboard.php");
    exit();
}

// Failed login
$_SESSION['admin_fail_count'] = ($_SESSION['admin_fail_count'] ?? 0) + 1;
if ($_SESSION['admin_fail_count'] >= $maxAttempts) {
    $_SESSION['admin_locked_until'] = time() + $lockSeconds;
}

admin_login_fail("Invalid username or password.", $username);