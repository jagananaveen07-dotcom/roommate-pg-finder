<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM pgs WHERE id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

if(mysqli_stmt_execute($stmt))
{
    echo "<script>
    alert('PG Deleted Successfully!');
    window.location='manage-pgs.php';
    </script>";
}
else
{
    echo "Something went wrong!";
}

mysqli_stmt_close($stmt);
?>