<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$city = $_POST['city'];
$budget = $_POST['budget'];
$gender = $_POST['gender'];
$preferences = $_POST['preferences'];

// Check if profile already exists
$check = "SELECT * FROM roommates WHERE email='$email'";
$result = mysqli_query($conn, $check);

if(mysqli_num_rows($result) > 0)
{
    echo "<script>
    alert('You have already created a roommate profile!');
    window.location='roommates.php';
    </script>";
}
else
{
    $sql = "INSERT INTO roommates
    (fullname, email, city, budget, gender, preferences)
    VALUES
    ('$fullname','$email','$city','$budget','$gender','$preferences')";

    if(mysqli_query($conn, $sql))
    {
        echo "<script>
        alert('Roommate Profile Created Successfully!');
        window.location='roommates.php';
        </script>";
    }
    else
    {
        echo mysqli_error($conn);
    }
}
?>