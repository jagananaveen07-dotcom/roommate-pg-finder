<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['email'];

$stmt = mysqli_prepare($conn,
"SELECT * FROM bookings
WHERE user_email=?
ORDER BY booking_date DESC");

mysqli_stmt_bind_param($stmt, "s", $user_email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Booking History</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav class="navbar">
    <h2>Roommate & PG Finder</h2>

    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="search.php">Search PG</a>
        <a href="saved_pgs.php">Saved PGs</a>
    </div>
</nav>

<h1 class="page-title">📖 Booking History</h1>
<p class="dashboard-subtitle">
Total Bookings :
<strong><?php echo mysqli_num_rows($result); ?></strong>
</p>

<section class="features">

<?php

if(mysqli_num_rows($result)>0)
{
    while($row=mysqli_fetch_assoc($result))
    {
?>

<div class="card">

<h2><?php echo htmlspecialchars($row['pg_name']); ?></h2>

<p><strong>City:</strong> <?php echo htmlspecialchars($row['city']); ?></p>

<p><strong>Rent:</strong> ₹<?php echo(int)$row['rent']; ?></p>

<p><strong>Booked On:</strong> <?php echo htmlspecialchars($row['booking_date']); ?></p>

<p><strong>Status:</strong>

<span class="available">

<?php echo htmlspecialchars($row['status']); ?>
</span>

</p>
<a href="cancel_booking.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Are you sure you want to cancel this booking?');">
<button class="reject-btn">Cancel Booking</button>
</a>

</div>

<?php
    }
}
else
{
   echo"
<div class='card' style='text-align:center; max-width:500px; margin:auto;'>

<h2>No Bookings Yet</h2>

<p>You haven't booked any PG yet.</p>

<a href='search.php'>
<button>Browse PGs</button>
</a>

</div>
";
}
?>

</section>
<?php mysqli_stmt_close($stmt); ?>
</body>
</html>