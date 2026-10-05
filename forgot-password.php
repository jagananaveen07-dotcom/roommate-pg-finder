<?php
session_start();
include("includes/db.php");
require_once("includes/mailer.php");

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please enter a valid email address.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, fullname, email FROM users WHERE email=?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            $otp = (string)random_int(100000, 999999);
            $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            $update = mysqli_prepare($conn, "UPDATE users SET otp_code=?, otp_expiry=? WHERE email=?");
            mysqli_stmt_bind_param($update, "sss", $otp, $expiry, $email);

            if (mysqli_stmt_execute($update)) {
                $mailResult = sendOtpEmail($email, $user['fullname'], $otp, 'reset');

                if ($mailResult['success']) {
                    $_SESSION['reset_password_email'] = $email;
                    $_SESSION['reset_password_name'] = $user['fullname'];
                    header("Location: reset-password.php");
                    exit();
                } else {
                    $error_msg = $mailResult['message'];
                }
            } else {
                $error_msg = "Database error. Please try again.";
            }
            mysqli_stmt_close($update);
        } else {
            $error_msg = "No account found registered with this email address.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Roommate & PG Finder</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .forgot-container {
            width: 420px;
            max-width: 90%;
            margin: 70px auto;
            background: #ffffff;
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
        }
        .forgot-icon {
            width: 60px;
            height: 60px;
            background: #eff6ff;
            color: #2563EB;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

<div class="forgot-container">
    <div class="forgot-icon">🔑</div>
    <h2 style="margin: 0 0 8px 0; color: #1e293b;">Forgot Password?</h2>
    <p style="color: #64748b; font-size: 14px; margin-bottom: 25px;">
        Enter your registered email address and we'll send you an OTP to reset your password.
    </p>

    <?php if(!empty($error_msg)): ?>
        <div style="background-color: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca; font-size: 14px; text-align: left;">
            ⚠️ <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="forgot-password.php">
        <div style="text-align: left; margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #334155; margin-bottom: 6px;">Email Address</label>
            <input type="email" name="email" placeholder="e.g. yourname@gmail.com" required style="width: 100%; box-sizing: border-box;" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>

        <button type="submit" style="width: 100%; padding: 12px; font-size: 16px; font-weight: bold; background-color: #2563EB;">
            Send Reset OTP
        </button>
    </form>

    <div style="margin-top: 25px; font-size: 14px;">
        <a href="login.php" style="color: #2563EB; text-decoration: none; font-weight: 600;">← Back to Login</a>
    </div>
</div>

</body>
</html>
