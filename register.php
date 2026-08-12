<?php
session_start();
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $city = trim($_POST['city']);
    $budget = trim($_POST['budget']);
    $gender = trim($_POST['gender']);
    $preferences = trim($_POST['preferences']);

    // Check if email already exists
    $check = mysqli_prepare($conn,
        "SELECT id FROM users WHERE email=?");

    mysqli_stmt_bind_param($check, "s", $email);

    mysqli_stmt_execute($check);

    $result = mysqli_stmt_get_result($check);

    if(mysqli_num_rows($result) > 0)
    {
        echo "<script>
        alert('Email already registered!');
        </script>";
    }
    else
    {
        $stmt = mysqli_prepare($conn,
        "INSERT INTO users
        (fullname,email,password,city,budget,gender,preferences)
        VALUES (?,?,?,?,?,?,?)");

        mysqli_stmt_bind_param(
            $stmt,
            "ssssiss",
            $fullname,
            $email,
            $password,
            $city,
            $budget,
            $gender,
            $preferences
        );

        if(mysqli_stmt_execute($stmt))
        {
            echo "<script>
            alert('Registration Successful!');
            window.location='login.php';
            </script>";
            exit();
        }
        else
        {
            echo mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_stmt_close($check);
}
?>
<!DOCTYPE html>
<html>

<head>

    <title>Register</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="register-container">

<form action="register.php" method="POST">

<h1>Create Account</h1>

<p>Join Roommate & PG Finder</p>

<label>Full Name</label><br>
<input type="text" name="fullname" placeholder="Enter your name" required>
<br><br>

<label>Email</label><br>
<input type="email" name="email" placeholder="Enter your email" required>
<br><br>

<label>Password</label><br>
<input type="password" name="password" placeholder="Enter your password" required>
<br><br>

<label>City</label><br>
<select name="city" required>
    <option value="">Select City</option>
    <option>Bangalore</option>
    <option>Hyderabad</option>
    <option>Chennai</option>
    <option>Visakhapatnam</option>
</select>
<br><br>

<label>Monthly Budget</label><br>
<input type="number" name="budget" placeholder="Enter Monthly Budget" required>
<br><br>

<label>Gender</label><br>

<div class="gender">
<label>
<input type="radio" name="gender" value="Male" required> Male
</label>

<label>
<input type="radio" name="gender" value="Female"> Female
</label>
</div>
<label>Preferences</label>
<br>
<textarea name="preferences" placeholder="Tell us about yourself"></textarea>
<br><br>
<button type="submit">Register</button>
<br><br>

<a href="login.php">
<button type="button">Back to Login</button>
</a>
</div>
</form>
<footer class="footer">

    <h3>Roommate & PG Finder</h3>

    <p>Helping freshers find affordable PGs and compatible roommates.</p>

    <p>© 2026 Roommate & PG Finder | All Rights Reserved</p>

</footer>
</body>

</html>