<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM pgs WHERE id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit PG</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="register-container">

<form action="update-pg.php" method="POST">

<h1>Edit PG</h1>

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<label>PG Name</label><br>
<input type="text" name="pg_name" value="<?php echo htmlspecialchars($row['pg_name']); ?>" required>

<br><br>

<label>City</label><br>
<input type="text" name="city" value="<?php echo htmlspecialchars($row['city']); ?>" required>

<br><br>

<label>Rent</label><br>
<input type="number" name="rent" value="<?php echo htmlspecialchars($row['rent']); ?>" required>

<br><br>

<label>Sharing</label><br>

<input
type="text"
name="sharing"
value="<?php echo htmlspecialchars($row['sharing']); ?>"
required>

<br><br>

<label>Availability</label><br>

<select name="availability" required>

<option value="Available"
<?php if($row['availability']=="Available") echo "selected"; ?>>
Available
</option>

<option value="Not Available"
<?php if($row['availability']=="Not Available") echo "selected"; ?>>
Not Available
</option>

</select>

<br><br>

<label>Description</label><br>

<textarea
name="description"
rows="5"
required><?php echo $row['description']; ?></textarea>

<button type="submit">
Update PG
</button>

</form>

</div>

</body>
</html>