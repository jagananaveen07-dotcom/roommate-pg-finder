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
    "SELECT s.*, p.id as pg_id, p.image1, p.rating, p.sharing, p.availability
     FROM saved_pgs s
     LEFT JOIN pgs p
     ON s.pg_name = p.pg_name
     AND s.city = p.city
     WHERE s.user_email=?
     ORDER BY s.id DESC"
);

mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$saved_count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Saved PGs - Roommate & PG Finder</title>

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

        <a href="saved_pgs.php" class="active-nav">
            Saved PGs
        </a>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</nav>


<div class="saved-pgs-wrapper">

    <h1 class="page-title">

        <i
            class="fa-solid fa-heart"
            style="color:#e63946;"
        ></i>

        My Saved PGs

    </h1>


    <p class="dashboard-subtitle">

        You have saved

        <strong>
            <?php echo $saved_count; ?>
        </strong>

        PG accommodation(s) for quick access.

    </p>


    <section
        class="pg-grid-container"
        style="margin-top:30px;"
    >

    <?php

    if($saved_count > 0)
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

            $avail = !empty($row['availability'])
                ? $row['availability']
                : 'Available';

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


                <span class="rating-badge-pill">

                    ⭐ <?php echo number_format($rating, 1); ?>

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

                        View Details

                    </a>

                <?php endif; ?>

            </div>


            <div
                class="listing-action-subrow"
                style="margin-top:12px;"
            >

                <!-- Book Now -->

                <form
                    action="book_pg.php"
                    method="POST"
                    class="inline-form"
                >

                    <input
                        type="hidden"
                        name="pg_name"
                        value="<?php echo htmlspecialchars($row['pg_name']); ?>"
                    >

                    <input
                        type="hidden"
                        name="city"
                        value="<?php echo htmlspecialchars($row['city']); ?>"
                    >

                    <input
                        type="hidden"
                        name="rent"
                        value="<?php echo (int)$row['rent']; ?>"
                    >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?php echo htmlspecialchars(csrf_token()); ?>"
                    >

                    <button
                        type="submit"
                        class="btn-book-quick"
                    >

                        <i class="fa-solid fa-calendar-check"></i>

                        Book Now

                    </button>

                </form>


                <!-- Remove -->

                <form
                    action="remove_saved.php"
                    method="POST"
                    class="inline-form"
                    onsubmit="return confirm('Remove this PG from saved list?');"
                >

                    <input
                        type="hidden"
                        name="saved_id"
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
                    >

                        <i class="fa-solid fa-trash-can"></i>

                        Remove

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
                class='fa-regular fa-heart no-res-icon'
                style='color:#e63946;'
            ></i>

            <h2>No Saved PGs Yet</h2>

            <p>
                Explore our listings and save your favorite PG
                accommodations to compare and book later.
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