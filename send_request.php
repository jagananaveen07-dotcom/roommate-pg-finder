<?php

session_start();
include("includes/db.php");

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}

$sender_email = $_SESSION['email'];

if(!isset($_POST['receiver_email']))
{
    echo "Receiver email is missing.";
    exit();
}

$receiver_email = trim($_POST['receiver_email']);

// Prevent sending request to yourself
if($sender_email == $receiver_email)
{
    echo "<script>
    alert('You cannot send a request to yourself!');
    window.location='roommates.php';
    </script>";
    exit();
}

// Check if request already exists
$check = mysqli_prepare(
    $conn,
    "SELECT id FROM roommate_requests
     WHERE sender_email=?
     AND receiver_email=?
     AND status IN ('Pending','Accepted')"
);

mysqli_stmt_bind_param(
    $check,
    "ss",
    $sender_email,
    $receiver_email
);

mysqli_stmt_execute($check);

$result = mysqli_stmt_get_result($check);

if(mysqli_num_rows($result) > 0)
{
    echo "<script>
    alert('Request already sent!');
    window.location='roommates.php';
    </script>";

    mysqli_stmt_close($check);
    exit();
}

// Insert new request
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO roommate_requests
    (sender_email, receiver_email)
    VALUES (?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $sender_email,
    $receiver_email
);

if(mysqli_stmt_execute($stmt))
{
    echo "<script>
    alert('Roommate Request Sent Successfully!');
    window.location='roommates.php';
    </script>";
}
else
{
    echo "Database Error: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_stmt_close($check);

?>