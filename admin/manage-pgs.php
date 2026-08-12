<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}
$stmt = mysqli_prepare($conn,
"SELECT * FROM pgs ORDER BY id DESC");

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>

<title>Manage PGs</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<nav class="navbar">

<h2>Admin Panel</h2>

<div class="menu">

<a href="admin-dashboard.php">Dashboard</a>

<a href="manage-users.php">Users</a>

<a href="view-bookings.php">Bookings</a>

<a href="admin-logout.php">Logout</a>

</div>

</nav>

<div class="admin-container">

<h1 class="admin-title">Manage PGs</h1>
<a href="add-pg.php">
    <button class="add-btn">
        + Add New PG
    </button>
</a>

<br><br>
<div class="table-container">

<table class="admin-table">

<tr>
    <th>ID</th>
    <th>PG Name</th>
    <th>City</th>
    <th>Rent</th>
    <th>Sharing</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo (int)$row['id']; ?></td>

<td><?php echo htmlspecialchars($row['pg_name']); ?></td>

<td><?php echo  htmlspecialchars($row['city']); ?></td>

<td>₹<?php echo (int)$row['rent']; ?></td>

<td><?php echo htmlspecialchars($row['sharing']); ?></td>
<td>

<?php

if($row['availability']=="Available")
{
    echo "<span class='available'>Available</span>";
}
else
{
    echo "<span class='notavailable'>Not Available</span>";
}

?>

</td>

<td>

<a href="edit-pg.php?id=<?php echo (int)$row['id']; ?>">

<button class="edit-btn">Edit</button>

</a>

<a
href="delete-pg.php?id=<?php echo (int)$row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this PG?');">
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