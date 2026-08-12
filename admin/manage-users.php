<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

$stmt = mysqli_prepare($conn,
"SELECT * FROM users ORDER BY id DESC");

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>

<title>Manage Users</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<nav class="navbar">

<h2>Admin Panel</h2>

<div class="menu">
<a href="admin-dashboard.php">Dashboard</a>
<a href="manage-pgs.php">Manage PGs</a>
<a href="admin-logout.php">Logout</a>

</div>

</nav>

<div class="table-container">

<h1 class="admin-title">Registered Users</h1>

<table class="admin-table"><tr>

<th>ID</th>
<th>Full Name</th>
<th>Email</th>
<th>City</th>
<th>Budget</th>
<th>Action</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo (int)$row['id']; ?></td>

<td><?php echo htmlspecialchars($row['fullname']); ?></td>

<td><?php echo htmlspecialchars($row['email']); ?></td>

<td><?php echo htmlspecialchars($row['city']); ?></td>

<td>₹<?php echo (int)$row['budget']; ?></td>

<td>

<a href="delete-user.php?id=<?php echo (int)$row['id']; ?>">
<a href="delete-user.php?id=<?php echo (int)$row['id']; ?>"
onclick="return confirm('Delete this user?');">
<button class="delete-btn">Delete</button>
</a>

</td>

</tr>

<?php
}
?>

</table>

</div>
<?php mysqli_stmt_close($stmt); ?>
</body>

</html>