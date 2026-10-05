<?php

session_start();

include("../includes/db.php");
include("../includes/csrf.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header("Location: manage-pgs.php");
    exit();
}

verify_csrf();

$id = filter_input(
    INPUT_POST,
    'id',
    FILTER_VALIDATE_INT
);

if(!$id)
{
    die("Invalid PG ID.");
}

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM pgs WHERE id=?"
);

if(!$stmt)
{
    die("Database error.");
}

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