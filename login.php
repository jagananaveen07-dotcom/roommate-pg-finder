<?php
session_start();
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST['email']);
$password = trim($_POST['password']);

$stmt = mysqli_prepare(
    $conn,
    "SELECT fullname, email, password
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
        $_SESSION['fullname'] = $row['fullname'];
        $_SESSION['email'] = $row['email'];

        header("Location: dashboard.php");
        exit();
    }
}

echo "<script>
alert('Invalid Email or Password!');
</script>";

mysqli_stmt_close($stmt);
}
   
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
        <link rel="stylesheet" href="css/style.css">

    
</head>
<body>

<div class="login-container">

<form method="POST">

<h1>Login</h1>

<p>Welcome back! Please login to continue.</p>

<label>Email</label><br>
<input type="email" name="email" placeholder="Enter your email" required>

<br><br>

<label>Password</label><br>
<input type="password" name="password" placeholder="Enter your password" required>

<br><br>

<button type="submit">Login</button>

<br><br>

<p>Don't have an account?</p>

<a href="register.php">Register Here</a>

</form>

</div>

<footer class="footer">
<h3>Roommate & PG Finder</h3>
<p>Helping freshers find affordable PGs and compatible roommates.</p>
<p>© 2026 Roommate & PG Finder | All Rights Reserved</p>
</footer>

</body>
</html>