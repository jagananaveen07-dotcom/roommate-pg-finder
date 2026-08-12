<?php

session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

$pg_name = trim($_POST['pg_name']);
$city = trim($_POST['city']);
$rent = (int)$_POST['rent'];
$sharing = trim($_POST['sharing']);
$description = trim($_POST['description']);
$availability = trim($_POST['availability']);

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO pgs
    (pg_name, city, rent, sharing, description, availability)
    VALUES (?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssisss",
    $pg_name,
    $city,
    $rent,
    $sharing,
    $description,
    $availability
);

if(mysqli_stmt_execute($stmt))
{
    echo "<script>
    alert('PG Added Successfully!');
    window.location='manage-pgs.php';
    </script>";
}
else
{
    echo "Something went wrong!";
}

mysqli_stmt_close($stmt);

?>