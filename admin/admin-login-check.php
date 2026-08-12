<?php
session_start();
include("../includes/db.php");

$username = trim($_POST['username']);
$password = trim($_POST['password']);

$stmt = mysqli_prepare(
    $conn,
    "SELECT username
     FROM admin
     WHERE username=? AND password=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $username,
    $password
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 1)
{
    $_SESSION['admin'] = $username;

    header("Location: admin-dashboard.php");
    exit();
}
else
{
    echo "<script>
    alert('Invalid Username or Password');
    window.location='admin-login.php';
    </script>";
}

mysqli_stmt_close($stmt);
?>