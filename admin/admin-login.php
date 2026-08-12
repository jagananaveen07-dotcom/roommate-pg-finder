<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Login</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="login-container">

<form action="admin-login-check.php" method="POST">

<h1>Admin Login</h1>

<p>Login to manage the system.</p>

<label>Username</label>

<input
type="text"
name="username"
placeholder="Enter Username"
required>

<br><br>

<label>Password</label>

<input
type="password"
name="password"
placeholder="Enter Password"
required>

<br><br>

<button type="submit">

Login

</button>

</form>

</div>

</body>

</html>