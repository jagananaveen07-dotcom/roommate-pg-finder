<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

$id = (int)$_POST['id'];
$pg_name = trim($_POST['pg_name']);
$city = trim($_POST['city']);
$rent = (int)$_POST['rent'];
$sharing = trim($_POST['sharing']);
$availability = trim($_POST['availability']);
$description = trim($_POST['description']);

$stmt = mysqli_prepare(
    $conn,
    "UPDATE pgs SET
    pg_name=?,
    city=?,
    rent=?,
    sharing=?,
    availability=?,
    description=?
    WHERE id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssisssi",
    $pg_name,
    $city,
    $rent,
    $sharing,
    $availability,
    $description,
    $id
);

if(mysqli_stmt_execute($stmt))
{
    echo "<script>
    alert('PG Updated Successfully!');
    window.location='manage-pgs.php';
    </script>";
}
else
{
    echo "Something went wrong!";
}

mysqli_stmt_close($stmt);
?>