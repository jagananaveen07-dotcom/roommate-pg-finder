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

if(!$row) {
    header("Location: manage-pgs.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit PG - <?php echo htmlspecialchars($row['pg_name']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

<nav class="navbar">
    <h2>Admin Panel</h2>
    <div class="menu">
        <a href="admin-dashboard.php">Dashboard</a>
        <a href="manage-pgs.php">Manage PGs</a>
        <a href="admin-logout.php">Logout</a>
    </div>
</nav>

<div class="register-container" style="width:650px;max-width:92%;margin:40px auto;text-align:left;padding:35px;">

<form action="update-pg.php" method="POST">

    <h1 style="text-align:center;color:#2563eb;font-size:28px;margin-bottom:25px;">
        <i class="fa-solid fa-pen-to-square"></i> Edit PG #<?php echo (int)$row['id']; ?>
    </h1>

    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div>
            <label style="font-weight:bold;color:#333;">PG Name *</label><br>
            <input type="text" name="pg_name" value="<?php echo htmlspecialchars($row['pg_name']); ?>" required style="width:100%;">
        </div>

        <div>
            <label style="font-weight:bold;color:#333;">City *</label><br>
            <input type="text" name="city" value="<?php echo htmlspecialchars($row['city']); ?>" required style="width:100%;">
        </div>
    </div>

    <br>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;">
        <div>
            <label style="font-weight:bold;color:#333;">Monthly Rent (₹) *</label><br>
            <input type="number" name="rent" value="<?php echo htmlspecialchars($row['rent']); ?>" required style="width:100%;">
        </div>

        <div>
            <label style="font-weight:bold;color:#333;">Sharing Type *</label><br>
            <input type="text" name="sharing" value="<?php echo htmlspecialchars($row['sharing']); ?>" required style="width:100%;">
        </div>

        <div>
            <label style="font-weight:bold;color:#333;">Rating (1.0 - 5.0)</label><br>
            <input type="number" step="0.1" min="1.0" max="5.0" name="rating" value="<?php echo htmlspecialchars($row['rating'] ?? 4.5); ?>" style="width:100%;">
        </div>
    </div>

    <br>

    <div>
        <label style="font-weight:bold;color:#333;">Locality / Full Address</label><br>
        <input type="text" name="address" value="<?php echo htmlspecialchars($row['address'] ?? ''); ?>" style="width:100%;">
    </div>

    <br>

    <div>
        <label style="font-weight:bold;color:#333;">Availability Status *</label><br>
        <select name="availability" required style="width:100%;">
            <option value="Available" <?php if($row['availability']=="Available") echo "selected"; ?>>Available</option>
            <option value="Not Available" <?php if($row['availability']=="Not Available") echo "selected"; ?>>Not Available</option>
        </select>
    </div>

    <br>

    <h3 style="color:#2563eb;font-size:16px;border-bottom:1px solid #e2e8f0;padding-bottom:5px;margin-top:10px;">
        <i class="fa-solid fa-images"></i> PG Image URLs / Paths (At least 3 images)
    </h3>

    <div style="margin-top:10px;">
        <label style="font-weight:bold;color:#444;font-size:13px;">Image 1 (Room & Bed View) *</label><br>
        <input type="text" name="image1" value="<?php echo htmlspecialchars($row['image1'] ?? 'Images/pgs/pg_bed_1.jpg'); ?>" required style="width:100%;">
    </div>

    <div style="margin-top:10px;">
        <label style="font-weight:bold;color:#444;font-size:13px;">Image 2 (Dining & Mess Hall) *</label><br>
        <input type="text" name="image2" value="<?php echo htmlspecialchars($row['image2'] ?? 'Images/pgs/pg_mess_1.jpg'); ?>" required style="width:100%;">
    </div>

    <div style="margin-top:10px;">
        <label style="font-weight:bold;color:#444;font-size:13px;">Image 3 (Building Exterior / Common Area) *</label><br>
        <input type="text" name="image3" value="<?php echo htmlspecialchars($row['image3'] ?? 'Images/pgs/pg_ext_1.jpg'); ?>" required style="width:100%;">
    </div>

    <br>

    <div>
        <label style="font-weight:bold;color:#333;">Amenities (Comma separated)</label><br>
        <textarea name="amenities" rows="3" style="width:100%;height:80px;"><?php echo htmlspecialchars($row['amenities'] ?? ''); ?></textarea>
    </div>

    <br>

    <div>
        <label style="font-weight:bold;color:#333;">PG Description *</label><br>
        <textarea name="description" rows="4" required style="width:100%;height:100px;"><?php echo htmlspecialchars($row['description']); ?></textarea>
    </div>

    <br>

    <div style="text-align:center;">
        <button type="submit" style="width:200px;">
            <i class="fa-solid fa-floppy-disk"></i> Update PG
        </button>
    </div>

</form>

</div>

</body>
</html>