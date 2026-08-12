<?php
session_start();

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

include("../includes/db.php");

// Counts
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pgs");
$pgs = mysqli_fetch_assoc($result)['total'];
$available = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM pgs WHERE availability='Available'"));

$not_available = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM pgs WHERE availability='Not Available'"));
$users = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users"));
$bookings = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM bookings"));
$requests = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM roommate_requests"));
?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Dashboard</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<nav class="navbar">

<h2>Admin Panel</h2>

<div class="menu">

<a href="admin-dashboard.php">Dashboard</a>

<a href="manage-pgs.php">Manage PGs</a>

<a href="manage-users.php">Users</a>

<a href="view-bookings.php">Bookings</a>

<a href="view-roommate-requests.php">Requests</a>

<a href="admin-logout.php">Logout</a>

</div>

</nav>

<section class="dashboard">

<h1>Welcome Admin</h1>

<p>Manage the Roommate & PG Finder system.</p>

<div class="dashboard-cards">

<div class="card">

<h2>Total PGs</h2>

<h1><?php echo (int)$pgs; ?></h1>

<a href="manage-pgs.php">
<button>Manage</button>
</a>

</div>

<div class="card">

<h2>Total Users</h2>

<h1><?php echo (int)$users; ?></h1>

<a href="manage-users.php">
<button>Manage</button>
</a>

</div>

<div class="card">

<h2>Total Bookings</h2>

<h1><?php echo (int)$bookings; ?></h1>

<a href="view-bookings.php">
<button>View</button>
</a>

</div>

<div class="card">

<h2>Roommate Requests</h2>

<h1><?php echo (int)$requests; ?></h1>

<a href="view-roommate-requests.php">
<button>View</button>
</a>

</div>
<div class="card">

<h2>Available PGs</h2>

<h1><?php echo (int)$available; ?></h1>

<p>Currently Open</p>

</div>

<div class="card">

<h2>Not Available</h2>

<h1><?php echo (int)$not_available; ?></h1>

<p>Currently Full</p>

</div>

</div>

</section>

<footer class="footer">

<h3>Roommate & PG Finder</h3>

<p>Admin Dashboard</p>

</footer>

</body>

</html>