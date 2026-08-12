<?php
include("includes/db.php");

$result = mysqli_query($conn, "SELECT * FROM pgs");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search PG</title>
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
    </div>
</nav>

<section class="features">

<?php
while($row = mysqli_fetch_assoc($result))
{
?>

<div class="card">

    <h2><?php echo htmlspecialchars($row['pg_name']); ?></h2>

    <p><strong>City:</strong> <?php echo htmlspecialchars($row['city']);?></p>
    <p><strong>Rent:</strong> ₹<?php echo (int)$row['rent']; ?></p>
    <p><strong>Sharing:</strong> <?php echo htmlspecialchars($row['sharing']); ?></p>
    <p>
<strong>Status:</strong>

<?php

if($row['availability']=="Available")
{
    echo "<span style='color:green;font-weight:bold;'>Available</span>";
}
else
{
    echo "<span style='color:red;font-weight:bold;'>Not Available</span>";
}

?>

</p>

    <p><?php echo htmlspecialchars($row['description']); ?></p>

    <!-- Book -->
        <?php

if($row['availability']=="Available")
{

?>

<form action="book_pg.php" method="POST">

<input type="hidden" name="pg_name" value="<?php echo $row['pg_name']; ?>">

<input type="hidden" name="city" value="<?php echo $row['city']; ?>">

<input type="hidden" name="rent" value="<?php echo $row['rent']; ?>">

<button type="submit">

Book Now

</button>

</form>

<?php

}
else
{

?>

<button disabled
style="background:red;cursor:not-allowed;">

Not Available

</button>

<?php

}

?>
    <br>

    <!-- Save -->
    <form action="save_pg.php" method="POST">
        <input type="hidden" name="pg_name" value="<?php echo htmlspecialchars($row['pg_name']); ?>">
        <input type="hidden" name="city" value="<?php echo htmlspecialchars($row['city']); ?>">
        <input type="hidden" name="rent" value="<?php echo htmlspecialchars($row['rent']); ?>">

        <button type="submit">❤️ Save</button>
    </form>

</div>

<?php
}
?>

</section>

</body>
</html>