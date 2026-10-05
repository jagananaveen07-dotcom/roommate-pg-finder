<?php
session_start();
include("../includes/db.php");
require_once("../includes/mailer.php");

$error         = "";
$cooldown      = 60;    // seconds between OTP emails
$otpValidMins  = 10;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $since = time() - ($_SESSION['admin_reset_last_sent'] ?? 0);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($since < $cooldown) {
        $error = "Please wait " . ($cooldown - $since) . " seconds before requesting another code.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, username FROM admin WHERE email=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if ($admin) {
            $otp    = (string)random_int(100000, 999999);
            $hash   = password_hash($otp, PASSWORD_DEFAULT);     // store only a hash of the OTP
            $expiry = date('Y-m-d H:i:s', time() + $otpValidMins * 60);

            $up = mysqli_prepare($conn, "UPDATE admin SET reset_otp_hash=?, reset_expiry=?, reset_attempts=0 WHERE id=?");
            mysqli_stmt_bind_param($up, "ssi", $hash, $expiry, $admin['id']);
            mysqli_stmt_execute($up);
            mysqli_stmt_close($up);

            $mail = sendOtpEmail($email, $admin['username'], $otp, 'reset');
            if (!$mail['success']) {
                error_log("Admin reset email failed: " . $mail['message']);   // check your server logs
            }
        }

        // Same response whether or not the email exists, so nobody can probe for admin emails
        $_SESSION['admin_reset_email']     = $email;
        $_SESSION['admin_reset_last_sent'] = time();
        header("Location: admin-reset-password.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Forgot Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="login-container">

<form method="POST">

    <h1>Forgot Password</h1>
    <p>Enter your admin email and we'll send a 6-digit code to reset your password.</p>

    <?php if ($error): ?>
        <div role="alert" style="background:#fee2e2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:18px;border:1px solid #fecaca;font-size:14px;text-align:left;">
            ⚠️ <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <label for="email">Admin Email</label>
    <input type="email" id="email" name="email" placeholder="Enter your admin email"
           autocomplete="email" required>

    <br><br>

    <button type="submit">Send Code</button>

    <p style="margin-top:18px;font-size:13px;">
        <a href="admin-login.php" style="color:#2563EB;text-decoration:none;font-weight:600;">&larr; Back to login</a>
    </p>

</form>

</div>

</body>

</html>