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
    header("Location: saved_pgs.php");
    exit();
}

verify_csrf();

if(!isset($_POST['saved_id']))
{
    echo "Saved PG ID is missing.";
    exit();
}

$id = (int)$_POST['saved_id'];

if($id <= 0)
{
    echo "Invalid saved PG ID.";
    exit();
}

$user_email = $_SESSION['email'];

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM saved_pgs
     WHERE id=? AND user_email=?"
);

if(!$stmt)
{
    echo "Database error.";
    exit();
}

mysqli_stmt_bind_param(
    $stmt,
    "is",
    $id,
    $user_email
);

if(mysqli_stmt_execute($stmt))
{
    if(mysqli_stmt_affected_rows($stmt) > 0)
    {
        echo "<script>
        alert('PG Removed Successfully!');
        window.location='saved_pgs.php';
        </script>";
    }
    else
    {
        echo "<script>
        alert('Saved PG not found or you are not authorised to remove it.');
        window.location='saved_pgs.php';
        </script>";
    }
}
else
{
    echo "Something went wrong!";
}

mysqli_stmt_close($stmt);

?>