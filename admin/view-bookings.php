<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

$stmt = mysqli_prepare($conn,
"SELECT * FROM bookings ORDER BY id DESC");

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>

<html>

<head>

<title>View Bookings</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<nav class="navbar">

<h2>Admin Panel</h2>

<div class="menu">

<a href="admin-dashboard.php">Dashboard</a>

<a href="manage-pgs.php">Manage PGs</a>

<a href="manage-users.php">Users</a>

<a href="admin-logout.php">Logout</a>

</div>

</nav>

<div class="admin-container">
    
<h1 class="admin-title">All Bookings</h1>
<div class="table-container">

<table class="admin-table">

<tr>

<th>ID</th>

<th>User Email</th>

<th>PG Name</th>

<th>City</th>

<th>Rent</th>
<th>Booking Date</th>
<th>Status</th>

</tr>

<?php

if(mysqli_num_rows($result)>0)
{
    while($row=mysqli_fetch_assoc($result))
    {
?>

<tr>

<td><?php echo (int)$row['id']; ?></td>

<td><?php echo htmlspecialchars($row['user_email']); ?></td>

<td><?php echo htmlspecialchars($row['pg_name']); ?></td>

<td><?php echo htmlspecialchars($row['city']); ?></td>

<td>₹<?php echo (int)$row['rent']; ?></td>
<td><?php echo htmlspecialchars($row['booking_date']); ?></td>

<td>
    <span class="status-booked">Booked</span>
</td>

</tr>

<?php
    }
}
else
{
?>

<tr>

<td colspan="7" style="text-align:center;">
No Bookings Yet
</td>

</tr>

<?php
}
?>

</table>

</div>
</div>
<?php mysqli_stmt_close($stmt); ?>
</body>

</html>