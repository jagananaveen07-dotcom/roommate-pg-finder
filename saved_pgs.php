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
"SELECT * FROM saved_pgs WHERE user_email=?");

mysqli_stmt_bind_param($stmt, "s", $user_email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Saved PGs</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1 class="page-title">❤️ My Saved PGs</h1>
<p class="dashboard-subtitle">
Total Saved PGs :
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

    <h2><?php echo htmlspecialchars($row['pg_name']); ?></h2>

    <p><strong>City:</strong> <?php echo htmlspecialchars($row['city']); ?></p>

    <p><strong>Rent:</strong> ₹<?php echo (int)$row['rent']; ?></p>

    <!-- Book Now -->
    <form action="book_pg.php" method="POST">

        <input type="hidden" name="pg_name" value="<?php echo htmlspecialchars($row['pg_name']); ?>">

        <input type="hidden" name="city" value="<?php echo htmlspecialchars($row['city']); ?>">

        <input type="hidden" name="rent" value="<?php echo (int)$row['rent']; ?>">

        <button type="submit">
            📖 Book Now
        </button>

    </form>

    <br>

    <!-- Remove -->
    <a href="remove_saved.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Remove this PG from saved list?');">
        <button class="remove-btn">
             Remove from saved
        </button>
    </a>

</div>

<?php
    }
}
else
{
    echo "
    <div class='card' style='max-width:500px;margin:auto;text-align:center;'>

        <h2>No Saved PGs</h2>

        <p>Start saving your favourite PGs.</p>

        <a href='search.php'>
            <button>Browse PGs</button>
        </a>

    </div>";
}
?>
<?php mysqli_stmt_close($stmt); ?>
</section>
</body>
</html>