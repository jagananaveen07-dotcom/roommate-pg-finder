<?php
session_start();

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Find Roommate</title>
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
    </div>

</nav>

<div class="register-container">

<form action="save_roommate.php" method="POST">

<h1>Find a Roommate</h1>

<p>Create your roommate profile.</p>

<label>Full Name</label><br>
<input
type="text"
name="fullname"
value="<?php echo ucwords($_SESSION['fullname']); ?>"
readonly>

<br><br>

<label>Email</label><br>
<input
type="email"
name="email"
value="<?php echo $_SESSION['email']; ?>"
readonly>

<br><br>

<label>City</label><br>

<select name="city" required>
    <option value="">Select City</option>
    <option>Bangalore</option>
    <option>Hyderabad</option>
    <option>Chennai</option>
    <option>Visakhapatnam</option>
</select>

<br><br>

<label>Monthly Budget</label><br>

<input
type="number"
name="budget"
placeholder="Enter Budget"
required>

<br><br>

<label>Gender</label><br>

<label>
<input
type="radio"
name="gender"
value="Male"
required> Male
</label>

<label>
<input
type="radio"
name="gender"
value="Female"> Female
</label>

<br><br>

<label>About Yourself</label><br>

<textarea
name="preferences"
rows="5"
placeholder="Example: Non-smoker, Working Professional, Vegetarian..."
required></textarea>

<br><br>

<button type="submit">
Create Profile
</button>

</form>

</div>

<footer class="footer">

<h3>Roommate & PG Finder</h3>

<p>Helping freshers find affordable PGs and compatible roommates.</p>

<p>© 2026 Roommate & PG Finder | All Rights Reserved</p>

</footer>

</body>

</html>