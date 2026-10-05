<?php
session_start();
include("../includes/db.php");

// Must come from the "forgot password" step
$email = $_SESSION['admin_reset_email'] ?? '';
if ($email === '') {
    header("Location: admin-forgot-password.php");
    exit();
}

$error       = "";
$maxAttempts = 5;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp      = trim($_POST['otp'] ?? '');
    $password = $_POST['new_password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!preg_match('/^\d{6}$/', $otp)) {
        $error = "Please enter the 6-digit code from your email.";
    } elseif (strlen($password) < 10 || strlen($password) > 72) {
        $error = "Password must be between 10 and 72 characters.";
    } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
        $error = "Password must contain at least one letter and one number.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, reset_otp_hash, reset_expiry, reset_attempts FROM admin WHERE email=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        $generic = "That code is invalid or has expired. Request a new one if needed.";

        if (!$admin || empty($admin['reset_otp_hash']) || empty($admin['reset_expiry'])
            || time() > strtotime($admin['reset_expiry'])
            || (int)$admin['reset_attempts'] >= $maxAttempts) {

            $error = $generic;

        } elseif (!password_verify($otp, $admin['reset_otp_hash'])) {
            // Wrong code: count the attempt, and burn the code after too many tries
            $attempts = (int)$admin['reset_attempts'] + 1;
            if ($attempts >= $maxAttempts) {
                $up = mysqli_prepare($conn, "UPDATE admin SET reset_attempts=?, reset_otp_hash=NULL, reset_expiry=NULL WHERE id=?");
            } else {
                $up = mysqli_prepare($conn, "UPDATE admin SET reset_attempts=? WHERE id=?");
            }
            mysqli_stmt_bind_param($up, "ii", $attempts, $admin['id']);
            mysqli_stmt_execute($up);
            mysqli_stmt_close($up);

            $error = $generic;

        } else {
            // Correct code: set the new password and invalidate the OTP
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $up = mysqli_prepare($conn, "UPDATE admin SET password=?, reset_otp_hash=NULL, reset_expiry=NULL, reset_attempts=0 WHERE id=?");
            mysqli_stmt_bind_param($up, "si", $newHash, $admin['id']);
            mysqli_stmt_execute($up);
            mysqli_stmt_close($up);

            unset($_SESSION['admin_reset_email'], $_SESSION['admin_reset_last_sent']);
            $_SESSION['admin_flash_success'] = "Password reset successfully. Please log in with your new password.";
            header("Location: admin-login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reset Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="login-container">

<form method="POST" autocomplete="off">

    <h1>Reset Password</h1>
    <p>If that email belongs to an admin, a 6-digit code was sent to it. It's valid for 10 minutes.</p>

    <?php if ($error): ?>
        <div role="alert" style="background:#fee2e2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:18px;border:1px solid #fecaca;font-size:14px;text-align:left;">
            ⚠️ <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <label for="otp">6-digit code</label>
    <input type="text" id="otp" name="otp" placeholder="123456" inputmode="numeric"
           pattern="\d{6}" maxlength="6" autocomplete="one-time-code" required>

    <br><br>

    <label for="new_password">New password</label>
    <input type="password" id="new_password" name="new_password" placeholder="At least 10 characters"
           minlength="10" maxlength="72" autocomplete="new-password" required>

    <br><br>

    <label for="confirm_password">Confirm new password</label>
    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password"
           minlength="10" maxlength="72" autocomplete="new-password" required>

    <br><br>

    <button type="submit">Reset Password</button>

    <p style="margin-top:18px;font-size:13px;">
        <a href="admin-forgot-password.php" style="color:#2563EB;text-decoration:none;font-weight:600;">Send a new code</a>
        &nbsp;|&nbsp;
        <a href="admin-login.php" style="color:#64748b;text-decoration:none;">Back to login</a>
    </p>

</form>

</div>

</body>

</html>