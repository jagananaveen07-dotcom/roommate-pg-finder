<?php
session_start();

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Add PG</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<nav class="navbar">

<h2>Admin Panel</h2>

<div class="menu">

<a href="admin-dashboard.php">Dashboard</a>
<a href="manage-pgs.php">Manage PGs</a>
<a href="logout.php">Logout</a>

</div>

</nav>

<div class="register-container">

<form action="save-pg.php" method="POST">

<h1>Add New PG</h1>

<label>PG Name</label><br>

<input
type="text"
name="pg_name"
required>

<br><br>

<label>City</label><br>

<input
type="text"
name="city"
required>

<br><br>

<label>Rent</label><br>

<input
type="number"
name="rent"
required>

<br><br>

<label>Sharing</label><br>

<input
type="text"
name="sharing"
placeholder="Single / Double / Triple"
required>

<br><br>
<label>Availability</label><br>

<select name="availability" required>

<option value="Available">Available</option>

<option value="Not Available">Not Available</option>

</select>

<br><br>

<label>Description</label><br>

<textarea
name="description"
rows="5"
required></textarea>

<br><br>

<button type="submit">

Add PG

</button>

</form>

</div>

</body>

</html>