<?php
session_start();
include("includes/db.php");
include("includes/csrf.php");

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['email'];

$stmt = mysqli_prepare($conn,
"SELECT * FROM roommates
WHERE email != ?
ORDER BY id DESC");

mysqli_stmt_bind_param($stmt, "s", $user_email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Roommates</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav class="navbar">

    <h2>Roommate & PG Finder</h2>
    <button class="mobile-nav-toggle" id="navToggle" type="button" aria-label="Toggle menu" aria-expanded="false">&#9776;</button>

    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="search.php">Search PG</a>
        <a href="saved_pgs.php">Saved PGs</a>
        <a href="booking-history.php">Bookings</a>
        <a href="find-roommate.php">Create Profile</a>
    </div>

</nav>

<h1 class="page-title">Available Roommates</h1>
<p class="dashboard-subtitle">
Available Profiles :
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

<h2>
<?php echo htmlspecialchars($row['fullname']); ?>
<?php if (str_ends_with(strtolower($row['email']), '@example.com')): ?>
<span style="display:inline-block;margin-left:6px;font-size:11px;font-weight:800;letter-spacing:.3px;text-transform:uppercase;background:#F1F5F9;color:#64748B;border-radius:6px;padding:2px 7px;vertical-align:middle;">Sample profile</span>
<?php endif; ?>
</h2>

<p><strong>City:</strong><?php echo htmlspecialchars($row['city']); ?></p>

<p>
<strong>Budget:</strong>

<span style="color:#2563EB;font-weight:bold;font-size:18px;">
₹<?php echo (int)$row['budget']; ?>
</span>

</p>
<p><strong>Gender:</strong> <?php echo htmlspecialchars($row['gender']); ?></p>

<p><strong>Preferences</strong></p>

<p style="font-style:italic;color:#555;">
<?php echo htmlspecialchars($row['preferences']); ?>
</p>

<form action="send_request.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">

<input
type="hidden"
name="receiver_email"
value="<?php echo htmlspecialchars($row['email']); ?>">

<button type="submit">
Send Roommate Request
</button>

</form>
</div>

<?php
    }
}
else
{
echo "
<div class='card' style='max-width:500px;margin:auto;text-align:center;'>

<h2>No Roommates Available</h2>

<p>Be the first to create your roommate profile.</p>

<a href='find-roommate.php'>
<button>Create Profile</button>
</a>

</div>";}

?>

</section>

<script>
    // Mobile menu: the stylesheet hides .menu under 768px until it gets the "show" class
    (function () {
        var btn = document.getElementById('navToggle');
        var menu = document.querySelector('.navbar .menu');
        if (!btn || !menu) return;
        btn.addEventListener('click', function () {
            var open = menu.classList.toggle('show');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    })();
</script>
</body>
</html>