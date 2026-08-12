<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}

if(isset($_POST['request_id']))
{
    $request_id = (int)$_POST['request_id'];

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE roommate_requests
         SET status='Rejected'
         WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $request_id
    );

    if(mysqli_stmt_execute($stmt))
    {
        echo "<script>
        alert('Request Rejected!');
        window.location='roommate-requests.php';
        </script>";
    }
    else
    {
        echo "Something went wrong!";
    }

    mysqli_stmt_close($stmt);
}
?>