<?php

session_start();

include("includes/db.php");
include("includes/csrf.php");

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE email=? LIMIT 1"
);

if(!$stmt)
{
    die("Database error.");
}

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $email
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if(!$user)
{
    die("User account not found.");
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Account Settings</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar">

<h2>Roommate & PG Finder</h2>

<div class="menu">

<a href="dashboard.php">Dashboard</a>

<a href="search.php">Search PG</a>

<a href="saved_pgs.php">Saved PGs</a>

<a href="booking-history.php">Bookings</a>

<a href="logout.php">Logout</a>

</div>

</nav>


<div class="register-container">

<form action="update_account.php" method="POST">

<h1 class="page-title">Account Settings</h1>

<p class="dashboard-subtitle">
Manage your personal information and password.
</p>


<label>Full Name</label>

<br>

<input
    type="text"
    name="fullname"
    value="<?php echo htmlspecialchars($user['fullname']); ?>"
    required
>

<br><br>


<label>Email Address</label>

<br>

<input
    type="email"
    value="<?php echo htmlspecialchars($user['email']); ?>"
    readonly
>

<br><br>


<label>City</label>

<br>

<select name="city" required>

<option value="Bangalore" <?php if($user['city']=="Bangalore") echo "selected"; ?>>
Bangalore
</option>

<option value="Hyderabad" <?php if($user['city']=="Hyderabad") echo "selected"; ?>>
Hyderabad
</option>

<option value="Chennai" <?php if($user['city']=="Chennai") echo "selected"; ?>>
Chennai
</option>

<option value="Visakhapatnam" <?php if($user['city']=="Visakhapatnam") echo "selected"; ?>>
Visakhapatnam
</option>

</select>

<br><br>


<label>New Password</label>

<br>

<input
    type="password"
    name="password"
    placeholder="Enter new password (optional)"
    autocomplete="new-password"
>

<br><br>


<!-- CSRF protection -->

<input
    type="hidden"
    name="csrf_token"
    value="<?php echo htmlspecialchars(csrf_token()); ?>"
>


<button type="submit">
Update Account
</button>

<br><br>


<a href="dashboard.php">

<button type="button" class="edit-btn">
Back to Dashboard
</button>

</a>

</form>

</div>

</body>

</html>