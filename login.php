<?php
session_start();
include("includes/db.php");
require_once("includes/mailer.php");

$error_msg = "";
$success_msg = "";

if (isset($_SESSION['flash_success'])) {
    $success_msg = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = mysqli_prepare(
        $conn,
        "SELECT fullname, email, password, is_verified
         FROM users
         WHERE email=?"
    );

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1)
    {
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password']))
        {
            // Check if email has been verified via OTP
            if(isset($row['is_verified']) && (int)$row['is_verified'] === 0)
            {
                // Generate a fresh OTP and prompt user to verify
                $otp = (string)random_int(100000, 999999);
                $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

                $updateOtp = mysqli_prepare($conn, "UPDATE users SET otp_code=?, otp_expiry=? WHERE email=?");
                mysqli_stmt_bind_param($updateOtp, "sss", $otp, $expiry, $email);
                mysqli_stmt_execute($updateOtp);
                mysqli_stmt_close($updateOtp);

                $mailResult = sendOtpEmail($row['email'], $row['fullname'], $otp, 'registration');
                $_SESSION['pending_verification_email'] = $row['email'];
                $_SESSION['pending_verification_name'] = $row['fullname'];

                echo "<script>
                    alert('Your email is not verified yet. We have sent a verification OTP to your email.');
                    window.location = 'verify-otp.php';
                </script>";
                exit();
            }

            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['email'] = $row['email'];

            header("Location: dashboard.php");
            exit();
        }
    }

    $error_msg = "Invalid Email or Password!";
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Roommate & PG Finder</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-container">

<form method="POST">

    <h1>Login</h1>
    <p>Welcome back! Please login to continue.</p>

    <?php if(!empty($success_msg)): ?>
        <div style="background-color: #dcfce7; color: #16a34a; padding: 12px; border-radius: 8px; margin-bottom: 18px; border: 1px solid #bbf7d0; font-size: 14px; text-align: left;">
            ✓ <?php echo htmlspecialchars($success_msg); ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($error_msg)): ?>
        <div style="background-color: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 18px; border: 1px solid #fecaca; font-size: 14px; text-align: left;">
            ⚠️ <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <label style="display: block; text-align: left; width: 300px; margin: 0 auto 5px auto; font-weight: bold; color: #334155; font-size: 14px;">Email</label>
    <input type="email" name="email" placeholder="Enter your email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

    <br><br>

    <label style="display: block; text-align: left; width: 300px; margin: 0 auto 5px auto; font-weight: bold; color: #334155; font-size: 14px;">Password</label>
    <input type="password" name="password" placeholder="Enter your password" required>

    <div style="display: flex; justify-content: flex-end; width: 320px; margin: 8px auto 16px auto;">
        <a href="forgot-password.php" style="color: #2563EB; font-size: 13px; text-decoration: none; font-weight: 600;">Forgot Password?</a>
    </div>

    <button type="submit" style="width: 320px;">Login</button>

    <br><br>

    <p style="margin-bottom: 8px;">Don't have an account?</p>

    <a href="register.php">
        <button type="button" style="background-color: #64748b; width: 320px;">Register Here</button>
    </a>

</form>

</div>

</body>
</html>