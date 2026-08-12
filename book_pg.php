<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['email'];

$pg_name = trim($_POST['pg_name']);
$city = trim($_POST['city']);
$rent = (int)$_POST['rent'];

// Check if already booked
$check = mysqli_prepare($conn,
"SELECT id FROM bookings
WHERE user_email=? AND pg_name=?");

mysqli_stmt_bind_param(
    $check,
    "ss",
    $user_email,
    $pg_name
);

mysqli_stmt_execute($check);

$result = mysqli_stmt_get_result($check);

if(mysqli_num_rows($result) > 0)
{
    echo "<script>
    alert('You have already booked this PG!');
    window.location='booking-history.php';
    </script>";
}
else
{
    $stmt = mysqli_prepare($conn,
    "INSERT INTO bookings
    (user_email, pg_name, city, rent)
    VALUES(?,?,?,?)");

    mysqli_stmt_bind_param(
        $stmt,
        "sssi",
        $user_email,
        $pg_name,
        $city,
        $rent
    );

    if(mysqli_stmt_execute($stmt))
    {
        echo "<script>
        alert('Booking Successful!');
        window.location='booking-history.php';
        </script>";
    }
    else
    {
        echo 'Something went wrong!';
    }

    mysqli_stmt_close($stmt);
}

mysqli_stmt_close($check);
?>