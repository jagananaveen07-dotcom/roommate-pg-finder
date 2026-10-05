<?php
session_start();
include("includes/db.php");

// Get PG ID from query parameter
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: search.php");
    exit();
}

$id = (int)$_GET['id'];

// Fetch PG details
$stmt = mysqli_prepare($conn, "SELECT * FROM pgs WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$pg_result = mysqli_stmt_get_result($stmt);
$pg = mysqli_fetch_assoc($pg_result);
mysqli_stmt_close($stmt);

if (!$pg) {
    header("Location: search.php");
    exit();
}

// Fetch all images for this PG from pg_images table
$img_stmt = mysqli_prepare($conn, "SELECT * FROM pg_images WHERE pg_id = ? ORDER BY is_primary DESC, id ASC");
mysqli_stmt_bind_param($img_stmt, "i", $id);
mysqli_stmt_execute($img_stmt);
$img_result = mysqli_stmt_get_result($img_stmt);
$images = [];
while ($img_row = mysqli_fetch_assoc($img_result)) {
    $images[] = $img_row;
}
mysqli_stmt_close($img_stmt);

// Fallback if no pg_images rows found: use image1, image2, image3 columns
if (empty($images)) {
    if (!empty($pg['image1'])) {
        $images[] = ['image_url' => $pg['image1'], 'caption' => 'Room & Bed View'];
    }
    if (!empty($pg['image2'])) {
        $images[] = ['image_url' => $pg['image2'], 'caption' => 'Dining & Mess Hall'];
    }
    if (!empty($pg['image3'])) {
        $images[] = ['image_url' => $pg['image3'], 'caption' => 'Building & Common Area'];
    }
}

// Fetch reviews for this PG
$rev_stmt = mysqli_prepare($conn, "SELECT * FROM pg_reviews WHERE pg_id = ? ORDER BY id DESC");
mysqli_stmt_bind_param($rev_stmt, "i", $id);
mysqli_stmt_execute($rev_stmt);
$rev_result = mysqli_stmt_get_result($rev_stmt);
$reviews = [];
while ($rev_row = mysqli_fetch_assoc($rev_result)) {
    $reviews[] = $rev_row;
}
mysqli_stmt_close($rev_stmt);

// Fetch similar PGs in the same city
$sim_stmt = mysqli_prepare($conn, "SELECT * FROM pgs WHERE city = ? AND id != ? LIMIT 3");
mysqli_stmt_bind_param($sim_stmt, "si", $pg['city'], $id);
mysqli_stmt_execute($sim_stmt);
$sim_result = mysqli_stmt_get_result($sim_stmt);
$similar_pgs = [];
while ($s_row = mysqli_fetch_assoc($sim_result)) {
    $similar_pgs[] = $s_row;
}
mysqli_stmt_close($sim_stmt);

// Parse amenities
$amenities_raw = !empty($pg['amenities']) ? $pg['amenities'] : "3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with Geyser, Washing Machine, 24x7 Power Backup, CCTV Surveillance & Biometric Access, RO Drinking Water";
$amenities_list = array_map('trim', explode(',', $amenities_raw));

$rating = !empty($pg['rating']) ? (float)$pg['rating'] : 4.5;
$reviews_count = !empty($pg['reviews_count']) ? (int)$pg['reviews_count'] : 35;
$address = !empty($pg['address']) ? $pg['address'] : htmlspecialchars($pg['city']) . ", India";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pg['pg_name']); ?> - PG Details & Reviews</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- Navigation Bar -->
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

<!-- Main Container -->
<div class="pg-detail-wrapper">

    <!-- Breadcrumbs -->
    <div class="breadcrumb-container">
        <a href="home.html"><i class="fa-solid fa-house"></i> Home</a> &rsaquo;
        <a href="search.php">Search PGs</a> &rsaquo;
        <a href="search.php?city=<?php echo urlencode($pg['city']); ?>"><?php echo htmlspecialchars($pg['city']); ?></a> &rsaquo;
        <span><?php echo htmlspecialchars($pg['pg_name']); ?></span>
    </div>

    <!-- PG Header -->
    <div class="pg-header-card">
        <div class="pg-header-left">
            <div class="pg-title-row">
                <h1 class="pg-main-title"><?php echo htmlspecialchars($pg['pg_name']); ?></h1>
                <span class="verified-tag"><i class="fa-solid fa-circle-check"></i> Verified PG</span>
            </div>
            <p class="pg-location-text">
                <i class="fa-solid fa-location-dot" style="color:#e63946;"></i>
                <strong><?php echo htmlspecialchars($address); ?></strong>
            </p>
            <div class="pg-badge-group">
                <span class="pg-pill-badge pill-city"><i class="fa-solid fa-city"></i> <?php echo htmlspecialchars($pg['city']); ?></span>
                <span class="pg-pill-badge pill-sharing"><i class="fa-solid fa-bed"></i> <?php echo htmlspecialchars($pg['sharing']); ?></span>
                <?php if($pg['availability'] == 'Available'): ?>
                    <span class="pg-pill-badge pill-available"><i class="fa-solid fa-circle-check"></i> Move-in Ready</span>
                <?php else: ?>
                    <span class="pg-pill-badge pill-unavailable"><i class="fa-solid fa-circle-xmark"></i> Currently Full</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="pg-header-right">
            <div class="rating-box-hero">
                <div class="rating-number">
                    <span><?php echo number_format($rating, 1); ?></span>
                    <i class="fa-solid fa-star"></i>
                </div>
                <div class="rating-meta">
                    <strong>Excellent</strong>
                    <span><?php echo $reviews_count; ?> Verified Reviews</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Gallery Showcase -->
    <div class="pg-gallery-card">
        <div class="gallery-container">
            <!-- Main Featured Image -->
            <div class="gallery-main-view">
                <img id="mainGalleryImage" src="<?php echo htmlspecialchars($images[0]['image_url']); ?>" alt="<?php echo htmlspecialchars($pg['pg_name']); ?>" onclick="openLightbox(this.src)">
                <div class="gallery-caption-badge" id="imageCaptionBadge">
                    <i class="fa-solid fa-camera"></i> <span id="captionText"><?php echo htmlspecialchars($images[0]['caption'] ?? 'Room & Bed View'); ?></span>
                </div>
                <button class="gallery-nav-btn prev-btn" onclick="prevImage()"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="gallery-nav-btn next-btn" onclick="nextImage()"><i class="fa-solid fa-chevron-right"></i></button>
                <button class="gallery-fullscreen-btn" onclick="openLightbox(document.getElementById('mainGalleryImage').src)" title="View Fullscreen">
                    <i class="fa-solid fa-expand"></i> View All Photos (<?php echo count($images); ?>)
                </button>
            </div>

            <!-- Thumbnails Strip -->
            <div class="gallery-thumbnails">
                <?php foreach($images as $idx => $img): ?>
                    <div class="thumb-item <?php echo $idx === 0 ? 'active-thumb' : ''; ?>" onclick="selectImage(<?php echo $idx; ?>, '<?php echo htmlspecialchars($img['image_url']); ?>', '<?php echo htmlspecialchars($img['caption'] ?? 'PG Photo'); ?>')">
                        <img src="<?php echo htmlspecialchars($img['image_url']); ?>" alt="Thumbnail <?php echo $idx + 1; ?>">
                        <span class="thumb-label"><?php echo htmlspecialchars($img['caption'] ?? ('Photo ' . ($idx + 1))); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Main Content Layout (2 Columns) -->
    <div class="pg-content-grid">

        <!-- Left Column: Details, Amenities, Rules, Reviews -->
        <div class="pg-details-left">

            <!-- Key Quick Highlights -->
            <div class="pg-section-card">
                <h3 class="section-heading"><i class="fa-solid fa-circle-info"></i> Key PG Highlights</h3>
                <div class="highlights-grid">
                    <div class="highlight-item">
                        <div class="hl-icon"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                        <div class="hl-text">
                            <span class="hl-title">Monthly Rent</span>
                            <span class="hl-val">₹<?php echo number_format($pg['rent']); ?> / month</span>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="hl-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <div class="hl-text">
                            <span class="hl-title">Security Deposit</span>
                            <span class="hl-val">1 Month (₹<?php echo number_format($pg['rent']); ?>)</span>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="hl-icon"><i class="fa-solid fa-utensils"></i></div>
                        <div class="hl-text">
                            <span class="hl-title">Food Included</span>
                            <span class="hl-val">3 Times Daily (Veg/Non-Veg)</span>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="hl-icon"><i class="fa-solid fa-calendar-check"></i></div>
                        <div class="hl-text">
                            <span class="hl-title">Notice Period</span>
                            <span class="hl-val">30 Days</span>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="hl-icon"><i class="fa-solid fa-clock"></i></div>
                        <div class="hl-text">
                            <span class="hl-title">Gate Closing Time</span>
                            <span class="hl-val">10:30 PM (Biometric)</span>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="hl-icon"><i class="fa-solid fa-handshake"></i></div>
                        <div class="hl-text">
                            <span class="hl-title">Brokerage Fee</span>
                            <span class="hl-val" style="color:#16a34a;font-weight:bold;">₹0 (Zero Brokerage)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="pg-section-card">
                <h3 class="section-heading"><i class="fa-solid fa-align-left"></i> About <?php echo htmlspecialchars($pg['pg_name']); ?></h3>
                <p class="pg-description-body">
                    <?php echo nl2br(htmlspecialchars($pg['description'])); ?>
                </p>
                <p class="pg-description-body" style="margin-top:10px; color:#555;">
                    Located in prime <strong><?php echo htmlspecialchars($address); ?></strong>, this accommodation is tailored for students and working professionals looking for a hygienic, peaceful, and fully furnished environment. Equipped with round-the-clock security, high-speed fiber internet, homestyle nutritious meals, and daily housekeeping.
                </p>
            </div>

            <!-- Amenities -->
            <div class="pg-section-card">
                <h3 class="section-heading"><i class="fa-solid fa-list-check"></i> Amenities & Facilities</h3>
                <div class="amenities-grid-modern">
                    <?php 
                    $amenity_icons = [
                        'food' => 'fa-solid fa-utensils',
                        'wi-fi' => 'fa-solid fa-wifi',
                        'wifi' => 'fa-solid fa-wifi',
                        'cleaning' => 'fa-solid fa-broom',
                        'housekeeping' => 'fa-solid fa-broom',
                        'bathroom' => 'fa-solid fa-bath',
                        'geyser' => 'fa-solid fa-shower',
                        'washing' => 'fa-solid fa-soap',
                        'backup' => 'fa-solid fa-bolt',
                        'cctv' => 'fa-solid fa-video',
                        'security' => 'fa-solid fa-shield-halved',
                        'ro' => 'fa-solid fa-bottle-water',
                        'water' => 'fa-solid fa-droplet',
                        'cupboard' => 'fa-solid fa-door-closed',
                        'table' => 'fa-solid fa-chair',
                        'parking' => 'fa-solid fa-square-parking',
                        'ac' => 'fa-solid fa-snowflake',
                        'gym' => 'fa-solid fa-dumbbell'
                    ];

                    foreach($amenities_list as $amenity): 
                        $icon = 'fa-solid fa-check-circle';
                        $low = strtolower($amenity);
                        foreach($amenity_icons as $key => $ic) {
                            if (strpos($low, $key) !== false) {
                                $icon = $ic;
                                break;
                            }
                        }
                    ?>
                        <div class="amenity-badge-card">
                            <i class="<?php echo $icon; ?>"></i>
                            <span><?php echo htmlspecialchars($amenity); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Food & Mess Menu Highlights -->
            <div class="pg-section-card">
                <h3 class="section-heading"><i class="fa-solid fa-bowl-rice"></i> Daily Food & Meal Schedule</h3>
                <div class="meal-schedule-grid">
                    <div class="meal-box">
                        <span class="meal-time"><i class="fa-solid fa-sun"></i> Breakfast</span>
                        <span class="meal-hours">7:30 AM - 9:30 AM</span>
                        <p>Idli, Dosa, Poha, Puri / Paratha with Chutney & Tea/Coffee</p>
                    </div>
                    <div class="meal-box">
                        <span class="meal-time"><i class="fa-solid fa-cloud-sun"></i> Lunch</span>
                        <span class="meal-hours">12:30 PM - 2:30 PM</span>
                        <p>Steamed Rice, Roti, Dal Tadka, Seasonal Sabzi, Curd & Pickle</p>
                    </div>
                    <div class="meal-box">
                        <span class="meal-time"><i class="fa-solid fa-moon"></i> Dinner</span>
                        <span class="meal-hours">7:30 PM - 10:00 PM</span>
                        <p>Hot Rotis, Paneer / Chicken Curry (Sundays/Wednesdays), Rice & Sambar</p>
                    </div>
                </div>
            </div>

            <!-- Ratings & Reviews -->
            <div class="pg-section-card" id="reviews-section">
                <div class="reviews-header-flex">
                    <h3 class="section-heading" style="margin:0;"><i class="fa-solid fa-star" style="color:#eab308;"></i> Resident Ratings & Reviews</h3>
                    <span class="total-reviews-pill"><?php echo count($reviews); ?> Reviews</span>
                </div>

                <!-- Rating Score Summary -->
                <div class="rating-breakdown-row">
                    <div class="overall-rating-card">
                        <div class="big-rating-number"><?php echo number_format($rating, 1); ?></div>
                        <div class="stars-row">
                            <?php 
                            for ($i = 1; $i <= 5; $i++) {
                                if ($rating >= $i) {
                                    echo '<i class="fa-solid fa-star"></i>';
                                } elseif ($rating >= $i - 0.5) {
                                    echo '<i class="fa-solid fa-star-half-stroke"></i>';
                                } else {
                                    echo '<i class="fa-regular fa-star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <span class="based-on">Based on <?php echo $reviews_count; ?> reviews</span>
                    </div>

                    <div class="rating-meters-col">
                        <div class="meter-item">
                            <span>Cleanliness & Hygiene</span>
                            <div class="meter-bar"><div class="meter-fill" style="width:94%;"></div></div>
                            <strong>4.8</strong>
                        </div>
                        <div class="meter-item">
                            <span>Food Quality & Taste</span>
                            <div class="meter-bar"><div class="meter-fill" style="width:90%;"></div></div>
                            <strong>4.6</strong>
                        </div>
                        <div class="meter-item">
                            <span>Safety & Security</span>
                            <div class="meter-bar"><div class="meter-fill" style="width:98%;"></div></div>
                            <strong>4.9</strong>
                        </div>
                        <div class="meter-item">
                            <span>Value for Money</span>
                            <div class="meter-bar"><div class="meter-fill" style="width:92%;"></div></div>
                            <strong>4.7</strong>
                        </div>
                    </div>
                </div>

                <!-- Individual Reviews -->
                <div class="reviews-list">
                    <?php if(!empty($reviews)): ?>
                        <?php foreach($reviews as $rev): ?>
                            <div class="single-review-card">
                                <div class="reviewer-meta-row">
                                    <div class="reviewer-avatar">
                                        <?php echo strtoupper(substr($rev['reviewer_name'], 0, 1)); ?>
                                    </div>
                                    <div class="reviewer-details">
                                        <h4><?php echo htmlspecialchars($rev['reviewer_name']); ?> <span class="verified-resident-tag"><i class="fa-solid fa-check"></i> Resident</span></h4>
                                        <div class="rev-stars">
                                            <?php 
                                            for($s = 1; $s <= 5; $s++) {
                                                echo ($s <= (int)$rev['rating']) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                                            }
                                            ?>
                                            <span class="rev-date"><?php echo date('M Y', strtotime($rev['created_at'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="review-comment">"<?php echo htmlspecialchars($rev['review_text']); ?>"</p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color:#777;text-align:center;padding:20px;">No reviews added yet. Be the first to review this PG!</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- Right Column: Sticky Booking & Inquiry Card -->
        <div class="pg-sidebar-right">
            <div class="sticky-booking-box">

                <div class="sidebar-price-tag">
                    <span class="price-currency">₹</span>
                    <span class="price-amount"><?php echo number_format($pg['rent']); ?></span>
                    <span class="price-unit">/ month</span>
                </div>

                <div class="sidebar-sharing-tag">
                    <i class="fa-solid fa-users"></i> <?php echo htmlspecialchars($pg['sharing']); ?> Occupancy
                </div>

                <div class="pricing-summary-box">
                    <div class="price-line">
                        <span>Monthly Rent:</span>
                        <strong>₹<?php echo number_format($pg['rent']); ?></strong>
                    </div>
                    <div class="price-line">
                        <span>Security Deposit (Refundable):</span>
                        <strong>₹<?php echo number_format($pg['rent']); ?></strong>
                    </div>
                    <div class="price-line">
                        <span>Maintenance & Electricity:</span>
                        <strong style="color:#16a34a;">Included</strong>
                    </div>
                    <div class="price-line">
                        <span>3-Times Daily Meals:</span>
                        <strong style="color:#16a34a;">Included</strong>
                    </div>
                    <hr class="price-divider">
                    <div class="price-line total-line">
                        <span>Total at Move-in:</span>
                        <strong class="total-amount">₹<?php echo number_format($pg['rent'] * 2); ?></strong>
                    </div>
                </div>

                <!-- Booking Action -->
                <?php if($pg['availability'] == 'Available'): ?>
                    <form action="book_pg.php" method="POST" class="sidebar-form">
                        <input type="hidden" name="pg_name" value="<?php echo htmlspecialchars($pg['pg_name']); ?>">
                        <input type="hidden" name="city" value="<?php echo htmlspecialchars($pg['city']); ?>">
                        <input type="hidden" name="rent" value="<?php echo (int)$pg['rent']; ?>">
                        <button type="submit" class="btn-primary-booking">
                            <i class="fa-solid fa-bolt"></i> Book This PG Now
                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn-primary-booking disabled-btn" disabled>
                        <i class="fa-solid fa-lock"></i> Currently Occupied
                    </button>
                <?php endif; ?>

                <!-- Save PG Action -->
                <form action="save_pg.php" method="POST" class="sidebar-form">
                    <input type="hidden" name="pg_name" value="<?php echo htmlspecialchars($pg['pg_name']); ?>">
                    <input type="hidden" name="city" value="<?php echo htmlspecialchars($pg['city']); ?>">
                    <input type="hidden" name="rent" value="<?php echo (int)$pg['rent']; ?>">
                    <button type="submit" class="btn-save-secondary">
                        <i class="fa-regular fa-heart"></i> Save to Favourites
                    </button>
                </form>

                <hr class="price-divider">

                <!-- Owner & Visit Support -->
                <div class="owner-contact-block">
                    <h4><i class="fa-solid fa-headset"></i> Need Help / Schedule Visit?</h4>
                    <p>Speak directly with the PG warden or book an in-person physical tour.</p>
                    
                    <div class="contact-actions-row">
                        <a href="https://api.whatsapp.com/send?text=Hi, I am interested in <?php echo urlencode($pg['pg_name'] . ' in ' . $pg['city']); ?> listed on Roommate PG Finder." target="_blank" class="btn-whatsapp">
                            <i class="fa-brands fa-whatsapp"></i> Chat WhatsApp
                        </a>
                        <a href="tel:+919876543210" class="btn-call">
                            <i class="fa-solid fa-phone"></i> Call Warden
                        </a>
                    </div>
                </div>

                <!-- Trust Guarantee Badge -->
                <div class="trust-guarantee-box">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div>
                        <strong>100% Verified Listing</strong>
                        <p>No hidden charges, zero brokerage, instant deposit receipt.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Similar PGs in City -->
    <?php if(!empty($similar_pgs)): ?>
    <div class="similar-pgs-container">
        <h2 class="similar-title"><i class="fa-solid fa-building"></i> Other Popular PGs in <?php echo htmlspecialchars($pg['city']); ?></h2>
        <div class="features" style="margin-top:20px;">
            <?php foreach($similar_pgs as $sim): ?>
                <div class="card pg-card-modern">
                    <div class="card-img-wrapper">
                        <img src="<?php echo htmlspecialchars(!empty($sim['image1']) ? $sim['image1'] : 'Images/pgs/pg_bed_1.jpg'); ?>" alt="<?php echo htmlspecialchars($sim['pg_name']); ?>">
                        <span class="card-sharing-badge"><?php echo htmlspecialchars($sim['sharing']); ?></span>
                        <span class="card-rating-badge">⭐ <?php echo number_format($sim['rating'] ?? 4.5, 1); ?></span>
                    </div>
                    <div class="card-info-content">
                        <h3><?php echo htmlspecialchars($sim['pg_name']); ?></h3>
                        <p class="card-loc"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($sim['city']); ?></p>
                        <p class="card-price">₹<?php echo number_format($sim['rent']); ?> <span class="price-sub">/ month</span></p>
                        <div class="card-actions-row">
                            <a href="pg_details.php?id=<?php echo (int)$sim['id']; ?>" class="btn-view-card">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- Fullscreen Lightbox Modal -->
<div id="imageLightboxModal" class="lightbox-modal" onclick="closeLightbox(event)">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <img class="lightbox-content" id="lightboxImg">
    <div id="lightboxCaption" class="lightbox-caption"></div>
    <button class="lightbox-nav-btn lb-prev" onclick="prevLightboxImage(event)">&#10094;</button>
    <button class="lightbox-nav-btn lb-next" onclick="nextLightboxImage(event)">&#10095;</button>
</div>

<!-- Footer -->
<footer class="footer">
    <h3>Roommate & PG Finder</h3>
    <p>Helping students and freshers find affordable verified PGs and compatible roommates across India.</p>
    <p>© 2026 Roommate & PG Finder | All Rights Reserved</p>
</footer>

<!-- Interactive Gallery & Lightbox Scripts -->
<script>
    const imagesData = <?php echo json_encode($images); ?>;
    let currentImageIndex = 0;

    function selectImage(index, url, caption) {
        currentImageIndex = index;
        const mainImg = document.getElementById('mainGalleryImage');
        const captionText = document.getElementById('captionText');
        
        mainImg.style.opacity = '0.4';
        setTimeout(() => {
            mainImg.src = url;
            captionText.innerText = caption;
            mainImg.style.opacity = '1';
        }, 150);

        // Update active thumbnail
        const thumbs = document.querySelectorAll('.gallery-thumbnails .thumb-item');
        thumbs.forEach((t, i) => {
            if (i === index) {
                t.classList.add('active-thumb');
            } else {
                t.classList.remove('active-thumb');
            }
        });
    }

    function nextImage() {
        currentImageIndex = (currentImageIndex + 1) % imagesData.length;
        const item = imagesData[currentImageIndex];
        selectImage(currentImageIndex, item.image_url, item.caption || 'PG Photo');
    }

    function prevImage() {
        currentImageIndex = (currentImageIndex - 1 + imagesData.length) % imagesData.length;
        const item = imagesData[currentImageIndex];
        selectImage(currentImageIndex, item.image_url, item.caption || 'PG Photo');
    }

    // Lightbox functions
    function openLightbox(imgSrc) {
        const modal = document.getElementById('imageLightboxModal');
        const lbImg = document.getElementById('lightboxImg');
        const lbCap = document.getElementById('lightboxCaption');
        
        modal.style.display = 'flex';
        lbImg.src = imgSrc;
        lbCap.innerText = imagesData[currentImageIndex]?.caption || "<?php echo htmlspecialchars($pg['pg_name']); ?>";
    }

    function closeLightbox(e) {
        if (!e || e.target.id === 'imageLightboxModal' || e.target.className === 'lightbox-close') {
            document.getElementById('imageLightboxModal').style.display = 'none';
        }
    }

    function nextLightboxImage(e) {
        if(e) e.stopPropagation();
        nextImage();
        document.getElementById('lightboxImg').src = imagesData[currentImageIndex].image_url;
        document.getElementById('lightboxCaption').innerText = imagesData[currentImageIndex].caption || '';
    }

    function prevLightboxImage(e) {
        if(e) e.stopPropagation();
        prevImage();
        document.getElementById('lightboxImg').src = imagesData[currentImageIndex].image_url;
        document.getElementById('lightboxCaption').innerText = imagesData[currentImageIndex].caption || '';
    }

    // Keyboard support
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('imageLightboxModal');
        if (modal.style.display === 'flex') {
            if (e.key === 'ArrowRight') nextLightboxImage();
            if (e.key === 'ArrowLeft') prevLightboxImage();
            if (e.key === 'Escape') closeLightbox();
        }
    });
</script>

</body>
</html>
