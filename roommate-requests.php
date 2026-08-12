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
"SELECT roommate_requests.*, users.fullname
FROM roommate_requests
JOIN users
ON roommate_requests.sender_email = users.email
WHERE roommate_requests.receiver_email=?
ORDER BY roommate_requests.id DESC");

mysqli_stmt_bind_param($stmt, "s", $user_email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Roommate Requests</title>
    <link rel="stylesheet" href="css/style.css">
    </head>
 <body>   
<nav class="navbar">

    <h2>Roommate & PG Finder</h2>

    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="roommates.php">Find Roommates</a>
        <a href="search.php">Search PG</a>
        <a href="logout.php">Logout</a>
    </div>

</nav>

<h1 class="page-title">Roommate Requests</h1>
<p class="dashboard-subtitle">
Total Requests :
<strong><?php echo mysqli_num_rows($result); ?></strong>
</p>

<section class="features">

<?php

if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {
?>

<div class="card">

    <h2><?php echo htmlspecialchars($row['fullname']); ?></h2>

    <p><strong>Email:</strong><?php echo htmlspecialchars($row['sender_email']); ?></p>

    <p><strong>Request Date:</strong> <?php echo htmlspecialchars($row['request_date']); ?></p>
<p><strong>Status:</strong>

<?php
if($row['status']=="Pending")
{
    echo "<span class='pending'>Pending</span>";
}
elseif($row['status']=="Accepted")
{
    echo "<span class='available'>Accepted</span>";
}
else
{
    echo "<span class='notavailable'>Rejected</span>";
}
?>

</p>

<?php
if($row['status']=="Pending")
{
?>

<form action="accept_request.php" method="POST">

    <input
    type="hidden"
    name="request_id"
    value="<?php echo (int)$row['id']; ?>">

    <button type="submit">
        Accept
    </button>

</form>

<br>

<form action="reject_request.php" method="POST">

    <input
    type="hidden"
    name="request_id"
    value="<?php echo (int)$row['id']; ?>">

    <button type="submit" class="reject-btn">
        Reject
    </button>

</form>

<?php
}
?>

</div>

<?php
    } // End while
}
else
{
    echo "
    <div class='card' style='max-width:500px;margin:auto;text-align:center;'>

        <h2>No Roommate Requests</h2>

        <p>No one has sent you a roommate request yet.</p>

        <a href='roommates.php'>
            <button>Find Roommates</button>
        </a>

    </div>";
}
?>
<footer class="footer">

<h3>Roommate & PG Finder</h3>

<p>Helping freshers find affordable PGs and compatible roommates.</p>

<p>© 2026 Roommate & PG Finder | All Rights Reserved</p>

</footer>
<?php mysqli_stmt_close($stmt); ?>
</body>

</html>