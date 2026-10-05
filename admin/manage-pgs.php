```php
<?php

session_start();

include("../includes/db.php");
include("../includes/csrf.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM pgs ORDER BY id DESC"
);

if(!$stmt)
{
    die("Database error.");
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage PGs - Admin Panel</title>

    <link rel="stylesheet" href="../css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>

<nav class="navbar">

    <h2>Admin Panel</h2>

    <div class="menu">

        <a href="admin-dashboard.php">Dashboard</a>
        <a href="manage-pgs.php" class="active-nav">PGs</a>
        <a href="manage-users.php">Users</a>
        <a href="view-bookings.php">Bookings</a>
        <a href="admin-logout.php">Logout</a>

    </div>

</nav>


<div class="admin-container" style="max-width:1200px;margin:30px auto;padding:0 20px;">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:15px;">

        <h1 class="admin-title" style="margin:0;">
            <i class="fa-solid fa-hotel"></i> Manage PGs
        </h1>

        <a href="add-pg.php">
            <button class="add-btn" style="margin:0;">
                <i class="fa-solid fa-plus"></i> Add New PG
            </button>
        </a>

    </div>


    <div class="table-container" style="background:white;padding:20px;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,0.08);overflow-x:auto;">

        <table class="admin-table">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>PG Name</th>
                    <th>City & Area</th>
                    <th>Rent</th>
                    <th>Sharing</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

            <?php

            while($row = mysqli_fetch_assoc($result))
            {
                $img = !empty($row['image1'])
                    ? "../" . $row['image1']
                    : "../Images/pgs/pg_bed_1.jpg";

                $rating = !empty($row['rating'])
                    ? (float)$row['rating']
                    : 4.5;

            ?>

                <tr>

                    <td>
                        <strong>#<?php echo (int)$row['id']; ?></strong>
                    </td>

                    <td>

                        <img
                            src="<?php echo htmlspecialchars($img); ?>"
                            alt="PG"
                            style="width:65px;height:45px;object-fit:cover;border-radius:6px;box-shadow:0 2px 5px rgba(0,0,0,0.1);"
                        >

                    </td>

                    <td style="text-align:left;font-weight:600;">

                        <a
                            href="../pg_details.php?id=<?php echo (int)$row['id']; ?>"
                            target="_blank"
                            style="color:#2563eb;text-decoration:none;"
                        >

                            <?php echo htmlspecialchars($row['pg_name']); ?>

                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px;"></i>

                        </a>

                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['city']); ?>
                    </td>

                    <td style="font-weight:bold;color:#16a34a;">
                        ₹<?php echo number_format((float)$row['rent']); ?>
                    </td>

                    <td>

                        <span style="background:#e0f2fe;color:#0369a1;padding:4px 8px;border-radius:12px;font-size:12px;font-weight:600;">

                            <?php echo htmlspecialchars($row['sharing']); ?>

                        </span>

                    </td>

                    <td>

                        <span style="background:#fef3c7;color:#b45309;padding:4px 8px;border-radius:12px;font-size:12px;font-weight:600;">

                            ⭐ <?php echo number_format($rating, 1); ?>

                        </span>

                    </td>

                    <td>

                        <?php if($row['availability']=="Available"): ?>

                            <span class="available">Available</span>

                        <?php else: ?>

                            <span class="notavailable">Not Available</span>

                        <?php endif; ?>

                    </td>

                    <td style="white-space:nowrap;">

                        <a
                            href="../pg_details.php?id=<?php echo (int)$row['id']; ?>"
                            target="_blank"
                            title="View Public Page"
                        >

                            <button type="button" class="edit-btn" style="background:#6366f1;">
                                <i class="fa-solid fa-eye"></i>
                            </button>

                        </a>


                        <a
                            href="edit-pg.php?id=<?php echo (int)$row['id']; ?>"
                            title="Edit PG Details"
                        >

                            <button type="button" class="edit-btn">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                        </a>


                        <!-- Secure Delete PG Form -->

                        <form
                            action="delete-pg.php"
                            method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Are you sure you want to delete this PG and its images?');"
                        >

                            <input
                                type="hidden"
                                name="id"
                                value="<?php echo (int)$row['id']; ?>"
                            >

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?php echo htmlspecialchars(csrf_token()); ?>"
                            >

                            <button
                                type="submit"
                                class="delete-btn"
                                title="Delete PG"
                            >

                                <i class="fa-solid fa-trash"></i>

                            </button>

                        </form>

                    </td>

                </tr>

            <?php
            }

            ?>

            </tbody>

        </table>

    </div>

</div>


<?php

mysqli_stmt_close($stmt);

?>

</body>
</html>
```
