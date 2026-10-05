<?php
session_start();
include("includes/db.php");
require_once("includes/mailer.php");

$error_msg = "";
$success_msg = "";

// Check if there is an active pending email or an email provided in query / post
$email = isset($_SESSION['pending_verification_email']) ? $_SESSION['pending_verification_email'] : '';
if (empty($email) && isset($_POST['email'])) {
    $email = trim($_POST['email']);
}

// Handle Resend OTP Request
if (isset($_POST['action']) && $_POST['action'] === 'resend' && !empty($email)) {
    // Check if user exists
    $stmt = mysqli_prepare($conn, "SELECT fullname, is_verified FROM users WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($res)) {
        if ($user['is_verified'] == 1) {
            $error_msg = "This account is already verified. You can login directly.";
        } else {
            $new_otp = (string)random_int(100000, 999999);
            $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            $update = mysqli_prepare($conn, "UPDATE users SET otp_code=?, otp_expiry=? WHERE email=?");
            mysqli_stmt_bind_param($update, "sss", $new_otp, $expiry, $email);

            if (mysqli_stmt_execute($update)) {
                $mailResult = sendOtpEmail($email, $user['fullname'], $new_otp, 'registration');
                
                if ($mailResult['success']) {
                    $success_msg = "A fresh 6-digit OTP has been sent to your email (" . htmlspecialchars($email) . ").";
                } else {
                    $error_msg = $mailResult['message'];
                }
            } else {
                $error_msg = "Could not update OTP. Please try again.";
            }
            mysqli_stmt_close($update);
        }
    } else {
        $error_msg = "No account found with this email address.";
    }
    mysqli_stmt_close($stmt);
}

// Handle OTP Verification Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'verify') {
    $otp_input = trim($_POST['otp'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error_msg = "Please provide the email address associated with your registration.";
    } elseif (empty($otp_input) || strlen($otp_input) !== 6 || !ctype_digit($otp_input)) {
        $error_msg = "Please enter a valid 6-digit OTP code.";
    } else {
        $stmt = mysqli_prepare($conn, 
            "SELECT id, fullname, email, city, budget, gender, preferences, otp_code, otp_expiry, is_verified 
             FROM users 
             WHERE email=?"
        );
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            if ($user['is_verified'] == 1) {
                $success_msg = "Account already verified! Redirecting to login...";
                echo "<script>
                    setTimeout(function(){ window.location = 'login.php'; }, 1500);
                </script>";
            } else {
                $currentTime = date('Y-m-d H:i:s');

                if (empty($user['otp_code'])) {
                    $error_msg = "No OTP request found. Please click 'Resend OTP'.";
                } elseif ($user['otp_code'] !== $otp_input) {
                    $error_msg = "Incorrect OTP code. Please double-check the code sent to your mail.";
                } elseif ($currentTime > $user['otp_expiry']) {
                    $error_msg = "This OTP has expired. Please click 'Resend OTP' to get a new code.";
                } else {
                    // Successful verification
                    $update = mysqli_prepare($conn, 
                        "UPDATE users 
                         SET is_verified=1, otp_code=NULL, otp_expiry=NULL 
                         WHERE email=?"
                    );
                    mysqli_stmt_bind_param($update, "s", $email);
                    
                    if (mysqli_stmt_execute($update)) {
                        // Synchronize with roommate table if profile details exist
                        $checkRm = mysqli_prepare($conn, "SELECT id FROM roommates WHERE email=?");
                        mysqli_stmt_bind_param($checkRm, "s", $email);
                        mysqli_stmt_execute($checkRm);
                        $rmRes = mysqli_stmt_get_result($checkRm);
                        
                        if (mysqli_num_rows($rmRes) === 0 && !empty($user['city'])) {
                            $insRm = mysqli_prepare($conn, "INSERT INTO roommates (fullname, email, city, budget, gender, preferences) VALUES (?, ?, ?, ?, ?, ?)");
                            mysqli_stmt_bind_param($insRm, "sssiss", $user['fullname'], $user['email'], $user['city'], $user['budget'], $user['gender'], $user['preferences']);
                            mysqli_stmt_execute($insRm);
                            mysqli_stmt_close($insRm);
                        }
                        mysqli_stmt_close($checkRm);

                        // Clear pending session and log the user in
                        unset($_SESSION['pending_verification_email']);
                        unset($_SESSION['pending_verification_name']);
                        $_SESSION['fullname'] = $user['fullname'];
                        $_SESSION['email'] = $user['email'];

                        echo "<script>
                            alert('Email verification successful! Welcome to Roommate & PG Finder.');
                            window.location = 'dashboard.php';
                        </script>";
                        exit();
                    } else {
                        $error_msg = "Verification error: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($update);
                }
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
    <title>Verify Email OTP - Roommate & PG Finder</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .otp-card {
            width: 440px;
            max-width: 90%;
            margin: 60px auto;
            background: #ffffff;
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
        }
        .otp-icon {
            width: 64px;
            height: 64px;
            background: #eff6ff;
            color: #2563EB;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 15px;
        }
        .otp-input-field {
            font-size: 28px;
            letter-spacing: 10px;
            text-align: center;
            font-weight: bold;
            color: #1e293b;
            width: 240px;
            padding: 12px;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .otp-input-field:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
            outline: none;
        }
        .alert-box {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: left;
        }
        .alert-danger {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .alert-success {
            background-color: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        .alert-info {
            background-color: #e0e7ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
        }
        .resend-section {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            font-size: 14px;
            color: #64748b;
        }
        .resend-link-btn {
            background: none;
            border: none;
            color: #2563EB;
            font-weight: bold;
            cursor: pointer;
            padding: 0;
            font-size: 14px;
            text-decoration: underline;
        }
        .resend-link-btn:disabled {
            color: #94a3b8;
            cursor: not-allowed;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="otp-card">
    <div class="otp-icon">✉️</div>
    <h2 style="margin: 0 0 8px 0; color: #1e293b;">Email Verification</h2>
    <p style="color: #64748b; font-size: 14px; margin-bottom: 25px;">
        We have sent a 6-digit One-Time Password (OTP) to: <br>
        <b style="color: #0f172a; font-size: 15px;"><?php echo !empty($email) ? htmlspecialchars($email) : 'your email address'; ?></b>
    </p>

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

    <form method="POST" action="verify-otp.php">
        <input type="hidden" name="action" value="verify">
        
        <?php if (empty($_SESSION['pending_verification_email'])): ?>
            <div style="margin-bottom: 15px; text-align: left;">
                <label style="font-size: 13px; color: #475569; font-weight: bold;">Registered Email:</label><br>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter registered email" required style="width: 100%; box-sizing: border-box; margin-top: 5px;">
            </div>
        <?php else: ?>
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <?php endif; ?>

        <div style="margin: 20px 0;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 10px;">
                Enter 6-Digit OTP
            </label>
            <input type="text" name="otp" class="otp-input-field" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" placeholder="••••••" autofocus required autocomplete="one-time-code">
        </div>

        <button type="submit" style="width: 100%; padding: 12px; font-size: 16px; font-weight: bold; background-color: #2563EB;">
            Verify & Proceed
        </button>
    </form>

    <div class="resend-section">
        <p style="margin: 0 0 10px 0;">Didn't receive the code?</p>
        <form method="POST" action="verify-otp.php" id="resendForm" style="display: inline;">
            <input type="hidden" name="action" value="resend">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <button type="submit" id="resendBtn" class="resend-link-btn">Resend OTP</button>
        </form>
        <div id="timerContainer" style="margin-top: 8px; font-size: 13px; color: #94a3b8; display: none;">
            Resend available in <span id="timerText" style="font-weight: bold; color: #64748b;">60</span>s
        </div>
    </div>

    <div style="margin-top: 25px; font-size: 13px;">
        <a href="register.php" style="color: #64748b; text-decoration: none;">← Register with different email</a> | 
        <a href="login.php" style="color: #64748b; text-decoration: none;">Back to Login</a>
    </div>
</div>

<script>
    // Resend OTP Countdown Timer
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
