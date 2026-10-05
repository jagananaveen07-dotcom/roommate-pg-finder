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
    header("Location: booking-history.php");
    exit();
}

verify_csrf();

if(!isset($_POST['booking_id']))
{
    echo "Booking ID is missing.";
    exit();
}

$id = (int)$_POST['booking_id'];

if($id <= 0)
{
    echo "Invalid booking ID.";
    exit();
}

$user_email = $_SESSION['email'];

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM bookings
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
        alert('Booking Cancelled Successfully!');
        window.location='booking-history.php';
        </script>";
    }
    else
    {
        echo "<script>
        alert('Booking not found or you are not authorised to cancel it.');
        window.location='booking-history.php';
        </script>";
    }
}
else
{
    echo "Something went wrong!";
}

mysqli_stmt_close($stmt);

?>