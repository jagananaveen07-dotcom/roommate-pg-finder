<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}

$id = (int)$_GET['id'];
$user_email = $_SESSION['email'];

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM saved_pgs
     WHERE id=? AND user_email=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "is",
    $id,
    $user_email
);

if(mysqli_stmt_execute($stmt))
{
    echo "<script>
    alert('PG Removed Successfully!');
    window.location='saved_pgs.php';
    </script>";
}
else
{
    echo "Something went wrong!";
}

mysqli_stmt_close($stmt);
?>