<?php

session_start();

include("includes/db.php");
include("includes/csrf.php");

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header("Location: dashboard.php");
    exit();
}

verify_csrf();

$email = $_SESSION['email'];

$fullname = trim($_POST['fullname'] ?? '');
$city = trim($_POST['city'] ?? '');
$password = trim($_POST['password'] ?? '');

if($fullname === '' || $city === '')
{
    echo "<script>
    alert('Full name and city cannot be empty.');
    window.location='dashboard.php';
    </script>";
    exit();
}

// If password is blank, don't update it
if($password === "")
{
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users
         SET fullname=?, city=?
         WHERE email=?"
    );

    if(!$stmt)
    {
        echo "Database error.";
        exit();
    }

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
    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users
         SET fullname=?, city=?, password=?
         WHERE email=?"
    );

    if(!$stmt)
    {
        echo "Database error.";
        exit();
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $fullname,
        $city,
        $hashed_password,
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