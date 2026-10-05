<?php
session_start();
include("includes/db.php");
require_once("includes/mailer.php");

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $raw_password = trim($_POST['password'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $budget = isset($_POST['budget']) ? intval(trim($_POST['budget'])) : 0;
    $gender = trim($_POST['gender'] ?? '');
    $preferences = trim($_POST['preferences'] ?? '');

    // Input Validations
    if (empty($fullname)) {
        $error_msg = "Please enter your full name.";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please enter a valid email address.";
    } elseif (strlen($raw_password) < 6) {
        $error_msg = "Password must be at least 6 characters long.";
    } elseif (empty($city)) {
        $error_msg = "Please select your city.";
    } elseif ($budget <= 0) {
        $error_msg = "Please enter a valid monthly budget amount.";
    } elseif (!in_array($gender, ['Male', 'Female', 'Other'])) {
        $error_msg = "Please select a valid gender option.";
    } else {
        $password = password_hash($raw_password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check = mysqli_prepare($conn, "SELECT id, is_verified FROM users WHERE email=?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        $otp = (string)random_int(100000, 999999);
        $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        if ($row = mysqli_fetch_assoc($result))
        {
            if ($row['is_verified'] == 1)
            {
                $error_msg = "This email is already registered and verified. Please login instead.";
            }
            else
            {
                // Update unverified user record with new details and fresh OTP
                $update = mysqli_prepare($conn, 
                    "UPDATE users 
                     SET fullname=?, password=?, city=?, budget=?, gender=?, preferences=?, otp_code=?, otp_expiry=?, is_verified=0 
                     WHERE email=?"
                );
                mysqli_stmt_bind_param($update, "sssisssss", $fullname, $password, $city, $budget, $gender, $preferences, $otp, $expiry, $email);
                
                if (mysqli_stmt_execute($update))
                {
                    $mailResult = sendOtpEmail($email, $fullname, $otp, 'registration');
                    
                    if ($mailResult['success']) {
                        $_SESSION['pending_verification_email'] = $email;
                        $_SESSION['pending_verification_name'] = $fullname;
                        header("Location: verify-otp.php");
                        exit();
                    } else {
                        $error_msg = $mailResult['message'];
                    }
                }
                else
                {
                    $error_msg = "Database Error: " . mysqli_error($conn);
                }
                mysqli_stmt_close($update);
            }
        }
        else
        {
            // Insert new unverified user
            $stmt = mysqli_prepare($conn,
                "INSERT INTO users
                (fullname, email, password, city, budget, gender, preferences, otp_code, otp_expiry, is_verified)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ssssissss",
                $fullname,
                $email,
                $password,
                $city,
                $budget,
                $gender,
                $preferences,
                $otp,
                $expiry
            );

            if (mysqli_stmt_execute($stmt))
            {
                $mailResult = sendOtpEmail($email, $fullname, $otp, 'registration');

                if ($mailResult['success']) {
                    $_SESSION['pending_verification_email'] = $email;
                    $_SESSION['pending_verification_name'] = $fullname;
                    header("Location: verify-otp.php");
                    exit();
                } else {
                    $error_msg = $mailResult['message'];
                }
            }
            else
            {
                $error_msg = "Database Error: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Roommate & PG Finder</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="register-container">

<form action="register.php" method="POST">

    <h1>Create Account</h1>
    <p>Join Roommate & PG Finder and verify with Email OTP</p>

    <?php if(!empty($error_msg)): ?>
        <div style="background-color: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 18px; border: 1px solid #fecaca; font-size: 14px;">
            <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <label>Full Name</label><br>
    <input type="text" name="fullname" placeholder="Enter your name" required value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
    <br><br>

    <label>Email</label><br>
    <input type="email" name="email" placeholder="Enter your email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    <br><br>

    <label>Password</label><br>
    <input type="password" name="password" placeholder="Enter your password" required>
    <br><br>

    <label>City</label><br>
    <select name="city" required style="width: 320px; padding: 10px; border: 1px solid lightgray; border-radius: 8px;">
        <option value="">Select City</option>
        <option <?php echo (isset($_POST['city']) && $_POST['city'] == 'Bangalore') ? 'selected' : ''; ?>>Bangalore</option>
        <option <?php echo (isset($_POST['city']) && $_POST['city'] == 'Hyderabad') ? 'selected' : ''; ?>>Hyderabad</option>
        <option <?php echo (isset($_POST['city']) && $_POST['city'] == 'Chennai') ? 'selected' : ''; ?>>Chennai</option>
        <option <?php echo (isset($_POST['city']) && $_POST['city'] == 'Visakhapatnam') ? 'selected' : ''; ?>>Visakhapatnam</option>
    </select>
    <br><br>

    <label>Monthly Budget (₹)</label><br>
    <input type="number" name="budget" placeholder="Enter Monthly Budget" required value="<?php echo isset($_POST['budget']) ? htmlspecialchars($_POST['budget']) : ''; ?>">
    <br><br>

    <label>Gender</label><br>
    <div class="gender">
        <label>
            <input type="radio" name="gender" value="Male" required <?php echo (!isset($_POST['gender']) || $_POST['gender'] == 'Male') ? 'checked' : ''; ?>> Male
        </label>
        <label>
            <input type="radio" name="gender" value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'checked' : ''; ?>> Female
        </label>
    </div>

    <label>Preferences</label><br>
    <textarea name="preferences" placeholder="Tell us about yourself (e.g. non-smoker, student, night owl)" style="width: 300px; height: 70px; padding: 10px; border: 1px solid lightgray; border-radius: 8px;"><?php echo isset($_POST['preferences']) ? htmlspecialchars($_POST['preferences']) : ''; ?></textarea>
    <br><br>

    <button type="submit">Register & Send OTP</button>
    <br><br>

    <p style="margin: 8px 0;">Already have an account?</p>
    <a href="login.php">
        <button type="button" style="background-color: #64748b;">Back to Login</button>
    </a>

</form>

</div>

</body>
</html>