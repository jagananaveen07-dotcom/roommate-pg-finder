<?php

session_start();
include("includes/db.php");
if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['email'];
// Total Bookings
$booking_count = mysqli_num_rows(
mysqli_query($conn,
"SELECT COUNT(*) FROM bookings
WHERE user_email='$user_email'")
);

// Total Saved PGs
$saved_count = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM saved_pgs
WHERE user_email='$user_email'")
);

// Pending Requests
$pending_count = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM roommate_requests
WHERE receiver_email='$user_email'
AND status='Pending'")
);

// Accepted Requests
$accepted_count = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM roommate_requests
WHERE receiver_email='$user_email'
AND status='Accepted'")
);


?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body> 
    <nav class="navbar">

    <h2>Roommate & PG Finder</h2>

    <div class="menu">
        <a href="home.html">Home</a>
        <a href="search.php">Search PG</a>
        <a href="roommates.php">Find Roommate</a>
       <a href="dashboard.php">Dashboard</a>
       <a href="logout.php">Logout</a>
    </div>

</nav>
<section class="dashboard">
    <h1>
Welcome,
<?php echo htmlspecialchars(ucwords($_SESSION['fullname'])); ?>
</h1>
    <p class="dashboard-subtitle">
Manage your bookings, saved PGs and roommate requests from one place.
</p>
    <div class="user-dashboard-cards">

<div class="card">

<h2>📖 My Bookings</h2>

<h3 class="dashboard-count">
<?php echo $booking_count; ?>
</h3>

<p class="dashboard-info">
Booking(s) Made
</p>
<p>View your bookings.</p>

<a href="booking-history.php">
<button>View bookings</button>
</a>

</div>


<div class="card">

<h2>❤️ Saved PGs</h2>

<h3 class="dashboard-count">
<?php echo $saved_count; ?>
</h3>

<p class="dashboard-info">
Saved PGs
</p>
<p>View your favourite PGs.</p>

<a href="saved_pgs.php">
<button>View Saved</button>
</a>

</div>


<div class="card">

<h2>👥 Roommate Requests</h2>

<h3 class="dashboard-count">
<?php echo $pending_count; ?>
</h3>

<p class="dashboard-info">
Pending Requests
</p>

<p style="margin-top:8px;color:green;font-weight:bold;">
Accepted :
<?php echo $accepted_count; ?>
</p>

<p>Manage roommate requests.</p>

<a href="roommate-requests.php">
<button>Manage Requests</button>
</a>

</div>


<div class="card">

<h2>⚙️ Account Settings</h2>

<h3 class="dashboard-count">
⚙️
</h3>

<p class="dashboard-info">
Manage Profile
</p>

<p>Update your profile and password.</p>

<a href="account-settings.php">
<button>Edit Profile</button>
</a>

</div>

</div>

<br><br>

<footer class="footer">

    <h3>Roommate & PG Finder</h3>

    <p>Helping freshers find affordable PGs and compatible roommates.</p>

    <p>© 2026 Roommate & PG Finder | All Rights Reserved</p>

</footer>
<script src="js/script.js"></script>
</body>
</html>