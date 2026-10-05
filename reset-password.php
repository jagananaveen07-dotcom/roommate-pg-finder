<?php
session_start();
include("includes/db.php");
require_once("includes/mailer.php");

$error_msg = "";
$success_msg = "";

$email = isset($_SESSION['reset_password_email']) ? $_SESSION['reset_password_email'] : '';
if (empty($email) && isset($_POST['email'])) {
    $email = trim($_POST['email']);
}

// Handle Resend OTP
if (isset($_POST['action']) && $_POST['action'] === 'resend' && !empty($email)) {
    $stmt = mysqli_prepare($conn, "SELECT fullname FROM users WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($res)) {
        $new_otp = (string)random_int(100000, 999999);
        $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $update = mysqli_prepare($conn, "UPDATE users SET otp_code=?, otp_expiry=? WHERE email=?");
        mysqli_stmt_bind_param($update, "sss", $new_otp, $expiry, $email);

        if (mysqli_stmt_execute($update)) {
            $mailResult = sendOtpEmail($email, $user['fullname'], $new_otp, 'reset');
            
            if ($mailResult['success']) {
                $success_msg = "A fresh 6-digit OTP has been sent to your email (" . htmlspecialchars($email) . ").";
            } else {
                $error_msg = $mailResult['message'];
            }
        } else {
            $error_msg = "Could not resend OTP. Please try again.";
        }
        mysqli_stmt_close($update);
    } else {
        $error_msg = "No account found with this email.";
    }
    mysqli_stmt_close($stmt);
}

// Handle Reset Password Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'reset') {
    $email = trim($_POST['email']);
    $otp_input = trim($_POST['otp']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($email)) {
        $error_msg = "Please provide your registered email address.";
    } elseif (empty($otp_input) || strlen($otp_input) !== 6 || !ctype_digit($otp_input)) {
        $error_msg = "Please enter a valid 6-digit OTP.";
    } elseif (strlen($new_password) < 6) {
        $error_msg = "Password must be at least 6 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $error_msg = "New password and confirm password do not match.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, fullname, otp_code, otp_expiry FROM users WHERE email=?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            $currentTime = date('Y-m-d H:i:s');

            if (empty($user['otp_code'])) {
                $error_msg = "No active OTP request found. Please request a new OTP.";
            } elseif ($user['otp_code'] !== $otp_input) {
                $error_msg = "Incorrect OTP code. Please check your email or resend.";
            } elseif ($currentTime > $user['otp_expiry']) {
                $error_msg = "This OTP has expired. Please click 'Resend OTP'.";
            } else {
                // Update password and clear OTP
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update = mysqli_prepare($conn, "UPDATE users SET password=?, otp_code=NULL, otp_expiry=NULL, is_verified=1 WHERE email=?");
                mysqli_stmt_bind_param($update, "ss", $hashed_password, $email);

                if (mysqli_stmt_execute($update)) {
                    unset($_SESSION['reset_password_email']);
                    unset($_SESSION['reset_password_name']);

                    $_SESSION['flash_success'] = "Password reset successfully! Please log in with your new password.";
                    header("Location: login.php");
                    exit();
                } else {
                    $error_msg = "Database error: " . mysqli_error($conn);
                }
                mysqli_stmt_close($update);
            }
        } else {
            $error_msg = "No account found with this email.";
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
    <title>Reset Password - Roommate & PG Finder</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .reset-card {
            width: 440px;
            max-width: 90%;
            margin: 50px auto;
            background: #ffffff;
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
        }
        .reset-icon {
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
        .alert-box {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
            text-align: left;
        }
        .alert-danger { background-color: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .alert-success { background-color: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
        .alert-info { background-color: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }
        .form-group { text-align: left; margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; color: #334155; margin-bottom: 6px; }
        .form-group input { width: 100%; box-sizing: border-box; }
        .otp-input-field {
            font-size: 24px;
            letter-spacing: 8px;
            text-align: center;
            font-weight: bold;
            color: #1e293b;
        }
    </style>
</head>
<body>

<div class="reset-card">
    <div class="reset-icon">🔒</div>
    <h2 style="margin: 0 0 8px 0; color: #1e293b;">Reset Password</h2>
    <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">
        Enter the 6-digit OTP sent to <br>
        <b style="color: #0f172a;"><?php echo !empty($email) ? htmlspecialchars($email) : 'your email'; ?></b>
    </p>

    <?php if (!empty($dev_notice)): ?>
        <div class="alert-box alert-info">
            <?php echo $dev_notice; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="alert-box alert-danger">
            ⚠️ <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_msg)): ?>
        <div class="alert-box alert-success">
            ✓ <?php echo htmlspecialchars($success_msg); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="reset-password.php">
        <input type="hidden" name="action" value="reset">

        <?php if (empty($_SESSION['reset_password_email'])): ?>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="Enter your registered email">
            </div>
        <?php else: ?>
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Enter 6-Digit OTP</label>
            <input type="text" name="otp" class="otp-input-field" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" placeholder="••••••" required autofocus>
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" placeholder="At least 6 characters" required>
        </div>

        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" placeholder="Re-enter new password" required>
        </div>

        <button type="submit" style="width: 100%; padding: 12px; font-size: 16px; font-weight: bold; background-color: #2563EB; margin-top: 8px;">
            Reset Password
        </button>
    </form>

    <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 13px; color: #64748b;">
        Didn't receive the code?
        <form method="POST" action="reset-password.php" style="display: inline;" id="resendForm">
            <input type="hidden" name="action" value="resend">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <button type="submit" id="resendBtn" style="background: none; border: none; color: #2563EB; font-weight: bold; cursor: pointer; padding: 0; text-decoration: underline;">
                Resend OTP
            </button>
        </form>
        <div id="timerContainer" style="margin-top: 6px; color: #94a3b8; display: none;">
            Resend available in <span id="timerText" style="font-weight: bold; color: #64748b;">60</span>s
        </div>
    </div>

    <div style="margin-top: 20px; font-size: 13px;">
        <a href="login.php" style="color: #64748b; text-decoration: none;">← Back to Login</a>
    </div>
</div>

<script>
    let timeLeft = 60;
    const resendBtn = document.getElementById('resendBtn');
    const timerContainer = document.getElementById('timerContainer');
    const timerText = document.getElementById('timerText');

    function startCooldown() {
        resendBtn.disabled = true;
        timerContainer.style.display = 'block';
        timeLeft = 60;

        const countdown = setInterval(() => {
            timeLeft--;
            timerText.innerText = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(countdown);
                resendBtn.disabled = false;
                timerContainer.style.display = 'none';
            }
        }, 1000);
    }

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'resend'): ?>
        startCooldown();
    <?php endif; ?>
</script>

</body>
</html>
