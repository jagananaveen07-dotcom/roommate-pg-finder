<?php

session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}
$stmt = mysqli_prepare($conn,
"SELECT * FROM roommate_requests ORDER BY id DESC");

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html>

<head>

<title>Roommate Requests</title>

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

<h1 class="admin-title">Roommate Requests</h1>

<table class="admin-table">
    
<tr>

<th>ID</th>

<th>Sender</th>

<th>Receiver</th>

<th>Status</th>

<th>Date</th>

</tr>

<?php

if(mysqli_num_rows($result)>0)
{
while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo (int)$row['id']; ?></td>

<td><?php echo htmlspecialchars($row['sender_email']); ?></td>
<td><?php echo htmlspecialchars($row['receiver_email']); ?></td>

<td>

<?php

if($row['status']=="Pending")
{
echo "<span style='color:orange;font-weight:bold;'>Pending</span>";
}
elseif($row['status']=="Accepted")
{
echo "<span style='color:green;font-weight:bold;'>Accepted</span>";
}
else
{
echo "<span style='color:red;font-weight:bold;'>Rejected</span>";
}

?>

</td>

<td><?php echo htmlspecialchars($row['request_date']); ?></td>

</tr>

<?php
}
}
else
{
?>

<tr>

<td colspan="5" style="text-align:center;">
No Requests Yet
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