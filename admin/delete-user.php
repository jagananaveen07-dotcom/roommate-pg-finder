<?php

session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

$id=$_GET['id'];

mysqli_query($conn,"DELETE FROM users WHERE id='$id'");

echo "<script>

alert('User Deleted Successfully!');

window.location='manage-users.php';

</script>";

?>