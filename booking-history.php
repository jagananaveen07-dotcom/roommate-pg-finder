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

$stmt = mysqli_prepare(
    $conn,
    "SELECT b.*, p.id as pg_id, p.image1, p.rating, p.sharing
     FROM bookings b
     LEFT JOIN pgs p
     ON b.pg_name = p.pg_name
     AND b.city = p.city
     WHERE b.user_email=?
     ORDER BY b.booking_date DESC"
);

mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$booking_count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Booking History - Roommate & PG Finder</title>

    <link rel="stylesheet" href="css/style.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

</head>

<body>

<nav class="navbar">

    <h2>Roommate & PG Finder</h2>

    <button
        class="mobile-nav-toggle"
        id="navToggle"
        type="button"
        aria-label="Toggle menu"
        aria-expanded="false"
    >
        &#9776;
    </button>

    <div class="menu">

        <a href="home.html">Home</a>

        <a href="search.php">Search PG</a>

        <a href="roommates.php">Find Roommate</a>

        <a href="saved_pgs.php">Saved PGs</a>

        <a href="dashboard.php" class="active-nav">Dashboard</a>

        <a href="logout.php">Logout</a>

    </div>

</nav>


<div class="saved-pgs-wrapper">

    <h1 class="page-title">
        <i class="fa-solid fa-book-bookmark" style="color:#2563eb;"></i>
        My Booking History
    </h1>

    <p class="dashboard-subtitle">
        Total Bookings Made :
        <strong><?php echo $booking_count; ?></strong>
    </p>


    <section class="pg-grid-container" style="margin-top:30px;">

    <?php

    if($booking_count > 0)
    {

        while($row = mysqli_fetch_assoc($result))
        {

            $img = !empty($row['image1'])
                ? $row['image1']
                : 'Images/pgs/pg_bed_1.jpg';

            $rating = !empty($row['rating'])
                ? (float)$row['rating']
                : 4.5;

            $sharing = !empty($row['sharing'])
                ? $row['sharing']
                : 'PG Stay';

            $pg_id = !empty($row['pg_id'])
                ? (int)$row['pg_id']
                : 0;

    ?>

    <div class="pg-listing-card">

        <div class="listing-img-box">

            <?php if($pg_id > 0): ?>

                <a href="pg_details.php?id=<?php echo $pg_id; ?>">

                    <img
                        src="<?php echo htmlspecialchars($img); ?>"
                        alt="<?php echo htmlspecialchars($row['pg_name']); ?>"
                    >

                </a>

            <?php else: ?>

                <img
                    src="<?php echo htmlspecialchars($img); ?>"
                    alt="<?php echo htmlspecialchars($row['pg_name']); ?>"
                >

            <?php endif; ?>


            <div class="img-overlay-top">

                <span class="sharing-badge-pill">

                    <i class="fa-solid fa-bed"></i>

                    <?php echo htmlspecialchars($sharing); ?>

                </span>


                <span class="status-badge-floating status-avail">

                    <?php echo htmlspecialchars($row['status']); ?>

                </span>

            </div>

        </div>


        <div class="listing-content-box">

            <h2 class="listing-title">

                <?php if($pg_id > 0): ?>

                    <a href="pg_details.php?id=<?php echo $pg_id; ?>">

                        <?php echo htmlspecialchars($row['pg_name']); ?>

                    </a>

                <?php else: ?>

                    <?php echo htmlspecialchars($row['pg_name']); ?>

                <?php endif; ?>

            </h2>


            <p class="listing-loc">

                <i
                    class="fa-solid fa-location-dot"
                    style="color:#e63946;"
                ></i>

                <?php echo htmlspecialchars($row['city']); ?>

            </p>


            <p style="margin:8px 0;font-size:13px;color:#666;">

                <i class="fa-regular fa-clock"></i>

                Booked on:

                <?php
                echo date(
                    'd M Y, h:i A',
                    strtotime($row['booking_date'])
                );
                ?>

            </p>


            <div
                class="listing-footer-row"
                style="margin-top:15px;"
            >

                <div class="price-display-block">

                    <span class="price-val">

                        ₹<?php echo number_format($row['rent']); ?>

                    </span>

                    <span class="price-period">

                        / month

                    </span>

                </div>


                <?php if($pg_id > 0): ?>

                    <a
                        href="pg_details.php?id=<?php echo $pg_id; ?>"
                        class="btn-view-details-cta"
                    >

                        <i class="fa-solid fa-eye"></i>

                        View PG

                    </a>

                <?php endif; ?>

            </div>


            <div
                class="listing-action-subrow"
                style="margin-top:12px;"
            >

                <form
                    action="cancel_booking.php"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to cancel this booking?');"
                >

                    <input
                        type="hidden"
                        name="booking_id"
                        value="<?php echo (int)$row['id']; ?>"
                    >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?php echo htmlspecialchars(csrf_token()); ?>"
                    >

                    <button
                        type="submit"
                        class="btn-remove-saved"
                        style="width:100%;text-align:center;display:block;border:none;cursor:pointer;"
                    >

                        <i class="fa-solid fa-ban"></i>

                        Cancel Booking

                    </button>

                </form>

            </div>

        </div>

    </div>

    <?php

        }

    }
    else
    {

        echo "

        <div
            class='no-results-box'
            style='max-width:550px;margin:auto;'
        >

            <i
                class='fa-solid fa-receipt no-res-icon'
                style='color:#2563eb;'
            ></i>

            <h2>No Bookings Yet</h2>

            <p>
                You haven't booked any PG accommodations yet.
                Start exploring verified PGs across top cities.
            </p>

            <a
                href='search.php'
                class='btn-view-details-cta'
                style='display:inline-block;margin-top:15px;'
            >

                <i class='fa-solid fa-magnifying-glass'></i>

                Browse PGs

            </a>

        </div>

        ";

    }

    mysqli_stmt_close($stmt);

    ?>

    </section>

</div>


<script>

(function () {

    var btn = document.getElementById('navToggle');

    var menu = document.querySelector('.navbar .menu');

    if (!btn || !menu) return;

    btn.addEventListener('click', function () {

        var open = menu.classList.toggle('show');

        btn.setAttribute(
            'aria-expanded',
            open ? 'true' : 'false'
        );

    });

})();

</script>

</body>

</html>