<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$fullname = trim($_POST['fullname']);
$city = trim($_POST['city']);
$password = trim($_POST['password']);

// If password is blank, don't update it
if($password == "")
{
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users
         SET fullname=?, city=?
         WHERE email=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $fullname,
        $city,
        $email
    );
}
else
{
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users
         SET fullname=?, city=?, password=?
         WHERE email=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $fullname,
        $city,
        $password,
        $email
    );
}

if(mysqli_stmt_execute($stmt))
{
    $_SESSION['fullname'] = $fullname;

    echo "<script>
    alert('Account Updated Successfully!');
    window.location='dashboard.php';
    </script>";
}
else
{
    echo "Something went wrong!";
}

mysqli_stmt_close($stmt);
?>