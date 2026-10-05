<?php
session_start();
include("includes/db.php");

// Search & Filter parameters
$city_filter = isset($_GET['city']) ? trim($_GET['city']) : '';
$sharing_filter = isset($_GET['sharing']) ? trim($_GET['sharing']) : '';
$max_rent = isset($_GET['max_rent']) && is_numeric($_GET['max_rent']) ? (int)$_GET['max_rent'] : 0;
$rating_filter = isset($_GET['min_rating']) && is_numeric($_GET['min_rating']) ? (float)$_GET['min_rating'] : 0;
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Build dynamic query
$query = "SELECT * FROM pgs WHERE 1=1";
$params = [];
$types = "";

if (!empty($city_filter)) {
    $query .= " AND city LIKE ?";
    $params[] = "%$city_filter%";
    $types .= "s";
}

if (!empty($sharing_filter)) {
    $query .= " AND sharing LIKE ?";
    $params[] = "%$sharing_filter%";
    $types .= "s";
}

if ($max_rent > 0) {
    $query .= " AND rent <= ?";
    $params[] = $max_rent;
    $types .= "i";
}

if ($rating_filter > 0) {
    $query .= " AND rating >= ?";
    $params[] = $rating_filter;
    $types .= "d";
}

if (!empty($search_query)) {
    $query .= " AND (pg_name LIKE ? OR city LIKE ? OR address LIKE ? OR description LIKE ?)";
    $q_term = "%$search_query%";
    $params[] = $q_term;
    $params[] = $q_term;
    $params[] = $q_term;
    $params[] = $q_term;
    $types .= "ssss";
}

$query .= " ORDER BY availability ASC, rating DESC, id DESC";

$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Verified PGs - Roommate & PG Finder</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<nav class="navbar">
    <h2>Roommate & PG Finder</h2>
    <div class="menu">
        <a href="home.html">Home</a>
        <a href="search.php" class="active-nav">Search PG</a>
        <a href="roommates.php">Find Roommate</a>
        <?php if(isset($_SESSION['email'])): ?>
            <a href="saved_pgs.php">Saved PGs</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>

<!-- Page Header & Filter Section -->
<div class="search-hero-section">
    <h1 class="page-title" style="margin-bottom:10px;">Find Verified PGs in India</h1>
    <p class="search-tagline">Browse curated paying guest accommodations with verified photos, ratings, food and amenities.</p>

    <!-- Search & Filter Form -->
    <form action="search.php" method="GET" class="filter-form-bar">
        <div class="filter-input-group">
            <i class="fa-solid fa-magnifying-glass filter-icon"></i>
            <input type="text" name="q" placeholder="Search by PG name, locality..." value="<?php echo htmlspecialchars($search_query); ?>" class="filter-search-input">
        </div>

        <div class="filter-select-group">
            <select name="city" class="filter-select">
                <option value="">All Cities</option>
                <option value="Bangalore" <?php if(strcasecmp($city_filter, 'Bangalore') === 0) echo 'selected'; ?>>Bangalore</option>
                <option value="Hyderabad" <?php if(strcasecmp($city_filter, 'Hyderabad') === 0) echo 'selected'; ?>>Hyderabad</option>
                <option value="Chennai" <?php if(strcasecmp($city_filter, 'Chennai') === 0) echo 'selected'; ?>>Chennai</option>
                <option value="Visakhapatnam" <?php if(strcasecmp($city_filter, 'Visakhapatnam') === 0 || strcasecmp($city_filter, 'vizag') === 0) echo 'selected'; ?>>Visakhapatnam</option>
            </select>
        </div>

        <div class="filter-select-group">
            <select name="sharing" class="filter-select">
                <option value="">All Sharing Types</option>
                <option value="Single" <?php if(stripos($sharing_filter, 'single') !== false) echo 'selected'; ?>>Single Occupancy</option>
                <option value="2 Sharing" <?php if(stripos($sharing_filter, '2') !== false || stripos($sharing_filter, 'double') !== false) echo 'selected'; ?>>2 Sharing / Double</option>
                <option value="3 Sharing" <?php if(stripos($sharing_filter, '3') !== false || stripos($sharing_filter, 'triple') !== false) echo 'selected'; ?>>3 Sharing / Triple</option>
                <option value="4 Sharing" <?php if(stripos($sharing_filter, '4') !== false) echo 'selected'; ?>>4 Sharing / Dorm</option>
            </select>
        </div>

        <div class="filter-select-group">
            <select name="max_rent" class="filter-select">
                <option value="">Budget (Any)</option>
                <option value="6000" <?php if($max_rent == 6000) echo 'selected'; ?>>Under ₹6,000</option>
                <option value="7500" <?php if($max_rent == 7500) echo 'selected'; ?>>Under ₹7,500</option>
                <option value="9000" <?php if($max_rent == 9000) echo 'selected'; ?>>Under ₹9,000</option>
                <option value="12000" <?php if($max_rent == 12000) echo 'selected'; ?>>Under ₹12,000</option>
            </select>
        </div>

        <div class="filter-select-group">
            <select name="min_rating" class="filter-select">
                <option value="">Rating (Any)</option>
                <option value="4.5" <?php if($rating_filter == 4.5) echo 'selected'; ?>>⭐ 4.5 & Above</option>
                <option value="4.0" <?php if($rating_filter == 4.0) echo 'selected'; ?>>⭐ 4.0 & Above</option>
            </select>
        </div>

        <button type="submit" class="filter-submit-btn">
            <i class="fa-solid fa-filter"></i> Apply Filters
        </button>

        <?php if(!empty($city_filter) || !empty($sharing_filter) || $max_rent > 0 || $rating_filter > 0 || !empty($search_query)): ?>
            <a href="search.php" class="filter-reset-btn" title="Reset all filters">
                <i class="fa-solid fa-rotate-left"></i> Reset
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Results Information -->
<div class="results-meta-bar">
    <span>Showing <strong><?php echo $total_count; ?></strong> verified PG accommodation(s)</span>
</div>

<!-- PG Cards Grid -->
<section class="pg-grid-container">

<?php
if ($total_count > 0) {
    while($row = mysqli_fetch_assoc($result))
    {
        $pg_img = !empty($row['image1']) ? $row['image1'] : 'Images/pgs/pg_bed_1.jpg';
        $rating = !empty($row['rating']) ? (float)$row['rating'] : 4.5;
        $reviews_count = !empty($row['reviews_count']) ? (int)$row['reviews_count'] : 25;
        $locality = !empty($row['address']) ? $row['address'] : htmlspecialchars($row['city']) . ", India";
?>

<div class="pg-listing-card">
    
    <!-- Image Header with Badges -->
    <div class="listing-img-box">
        <a href="pg_details.php?id=<?php echo (int)$row['id']; ?>">
            <img src="<?php echo htmlspecialchars($pg_img); ?>" alt="<?php echo htmlspecialchars($row['pg_name']); ?>" loading="lazy">
        </a>

        <div class="img-overlay-top">
            <span class="sharing-badge-pill">
                <i class="fa-solid fa-bed"></i> <?php echo htmlspecialchars($row['sharing']); ?>
            </span>
            <span class="rating-badge-pill">
                <i class="fa-solid fa-star"></i> <?php echo number_format($rating, 1); ?> (<?php echo $reviews_count; ?>)
            </span>
        </div>

        <?php if($row['availability'] == 'Available'): ?>
            <span class="status-badge-floating status-avail">Available</span>
        <?php else: ?>
            <span class="status-badge-floating status-unavail">Not Available</span>
        <?php endif; ?>
    </div>

    <!-- Card Content -->
    <div class="listing-content-box">
        <div class="listing-title-row">
            <h2 class="listing-title">
                <a href="pg_details.php?id=<?php echo (int)$row['id']; ?>">
                    <?php echo htmlspecialchars($row['pg_name']); ?>
                </a>
            </h2>
        </div>

        <p class="listing-loc">
            <i class="fa-solid fa-location-dot" style="color:#e63946;"></i>
            <?php echo htmlspecialchars($locality); ?>
        </p>

        <!-- Amenity mini chips -->
        <div class="listing-amenities-mini">
            <span class="amenity-chip"><i class="fa-solid fa-utensils"></i> 3x Food</span>
            <span class="amenity-chip"><i class="fa-solid fa-wifi"></i> WiFi</span>
            <span class="amenity-chip"><i class="fa-solid fa-broom"></i> Cleaning</span>
            <span class="amenity-chip"><i class="fa-solid fa-shower"></i> Geyser</span>
        </div>

        <p class="listing-desc">
            <?php echo htmlspecialchars(mb_strimwidth($row['description'], 0, 95, '...')); ?>
        </p>

        <!-- Price and Actions -->
        <div class="listing-footer-row">
            <div class="price-display-block">
                <span class="price-val">₹<?php echo number_format($row['rent']); ?></span>
                <span class="price-period">/ month</span>
            </div>

            <!-- View Details Primary CTA -->
            <a href="pg_details.php?id=<?php echo (int)$row['id']; ?>" class="btn-view-details-cta">
                <i class="fa-solid fa-eye"></i> View Details
            </a>
        </div>

        <!-- Secondary Action Buttons -->
        <div class="listing-action-subrow">
            <!-- Book Now Action -->
            <?php if($row['availability'] == "Available"): ?>
                <form action="book_pg.php" method="POST" class="inline-form">
                    <input type="hidden" name="pg_name" value="<?php echo htmlspecialchars($row['pg_name']); ?>">
                    <input type="hidden" name="city" value="<?php echo htmlspecialchars($row['city']); ?>">
                    <input type="hidden" name="rent" value="<?php echo (int)$row['rent']; ?>">
                    <button type="submit" class="btn-book-quick">
                        <i class="fa-solid fa-calendar-check"></i> Book Now
                    </button>
                </form>
            <?php else: ?>
                <button disabled class="btn-book-quick btn-disabled">
                    <i class="fa-solid fa-lock"></i> Full
                </button>
            <?php endif; ?>

            <!-- Save PG Action -->
            <form action="save_pg.php" method="POST" class="inline-form">
                <input type="hidden" name="pg_name" value="<?php echo htmlspecialchars($row['pg_name']); ?>">
                <input type="hidden" name="city" value="<?php echo htmlspecialchars($row['city']); ?>">
                <input type="hidden" name="rent" value="<?php echo (int)$row['rent']; ?>">
                <button type="submit" class="btn-save-quick" title="Save to Favourites">
                    <i class="fa-regular fa-heart"></i> Save
                </button>
            </form>
        </div>

    </div>

</div>

<?php
    }
} else {
?>
    <div class="no-results-box">
        <i class="fa-solid fa-hotel no-res-icon"></i>
        <h2>No PGs Match Your Search Criteria</h2>
        <p>Try adjusting your city, budget, or sharing type filters to find available accommodations.</p>
        <a href="search.php" class="btn-view-details-cta" style="display:inline-block;margin-top:15px;">
            <i class="fa-solid fa-rotate-left"></i> View All PGs
        </a>
    </div>
<?php
}
mysqli_stmt_close($stmt);
?>

</section>

<footer class="footer">
    <h3>Roommate & PG Finder</h3>
    <p>Helping students and freshers find affordable verified PGs and compatible roommates across India.</p>
    <p>© 2026 Roommate & PG Finder | All Rights Reserved</p>
</footer>

</body>
</html>