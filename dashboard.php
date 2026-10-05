<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Asia/Kolkata');

$user_email = $_SESSION['email'];

/* ---------- small helpers (all queries are prepared statements) ---------- */
function db_all(mysqli $conn, string $sql, string $types = '', array $params = []): array
{
    $stmt = mysqli_prepare($conn, $sql);
    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $rows = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    mysqli_stmt_close($stmt);
    return $rows;
}

function db_count(mysqli $conn, string $sql, string $types = '', array $params = []): int
{
    $rows = db_all($conn, $sql, $types, $params);
    return $rows ? (int)array_values($rows[0])[0] : 0;
}

function is_sample_email(string $email): bool
{
    return str_ends_with(strtolower($email), '@example.com');
}

/* ---------- user + stats ---------- */
$user   = db_all($conn, "SELECT fullname, city, budget, gender FROM users WHERE email=? LIMIT 1", "s", [$user_email])[0] ?? [];
$city   = trim($user['city'] ?? '');
$budget = (int)($user['budget'] ?? 0);

$fullname  = $user['fullname'] ?? ($_SESSION['fullname'] ?? 'there');
$firstName = ucfirst(strtolower(explode(' ', trim($fullname))[0]));

$booking_count  = db_count($conn, "SELECT COUNT(*) FROM bookings WHERE user_email=?", "s", [$user_email]);
$saved_count    = db_count($conn, "SELECT COUNT(*) FROM saved_pgs WHERE user_email=?", "s", [$user_email]);
$pending_count  = db_count($conn, "SELECT COUNT(*) FROM roommate_requests WHERE receiver_email=? AND status='Pending'", "s", [$user_email]);
$accepted_count = db_count($conn, "SELECT COUNT(*) FROM roommate_requests WHERE (receiver_email=? OR sender_email=?) AND status='Accepted'", "ss", [$user_email, $user_email]);
$has_profile    = db_count($conn, "SELECT COUNT(*) FROM roommates WHERE email=?", "s", [$user_email]) > 0;

/* ---------- recommended PGs: city + budget, then city, then top rated ---------- */
$pgCols    = "id, pg_name, city, rent, sharing, rating, image1";
$recs      = [];
$recsTitle = "Top-rated PGs for you";

// Some PGs appear more than once under the same name; show each name once
function unique_pgs(array $rows, int $limit = 3): array
{
    $seen = [];
    $out  = [];
    foreach ($rows as $r) {
        $key = strtolower(trim($r['pg_name']));
        if (isset($seen[$key])) { continue; }
        $seen[$key] = true;
        $out[] = $r;
        if (count($out) >= $limit) { break; }
    }
    return $out;
}

if ($city !== '' && $budget > 0) {
    $recs = db_all($conn, "SELECT $pgCols FROM pgs WHERE availability='Available' AND city=? AND rent<=? ORDER BY rating DESC, rent ASC LIMIT 12", "si", [$city, $budget]);
    $recs = unique_pgs($recs);
    if ($recs) {
        $recsTitle = "PGs in " . $city . " within your budget";
    }
}
if (!$recs && $city !== '') {
    $recs = db_all($conn, "SELECT $pgCols FROM pgs WHERE availability='Available' AND city=? ORDER BY rating DESC LIMIT 12", "s", [$city]);
    $recs = unique_pgs($recs);
    if ($recs) {
        $recsTitle = "Popular PGs in " . $city;
    }
}
if (!$recs) {
    $recs = db_all($conn, "SELECT $pgCols FROM pgs WHERE availability='Available' ORDER BY rating DESC LIMIT 12");
    $recs = unique_pgs($recs);
}

/* ---------- suggested roommates: same city closest budget, else anyone ---------- */
$mates = db_all($conn, "SELECT fullname, city, budget, gender, preferences, email FROM roommates WHERE email<>? AND city=? ORDER BY ABS(budget-?) ASC LIMIT 3", "ssi", [$user_email, $city, $budget]);
if (!$mates) {
    $mates = db_all($conn, "SELECT fullname, city, budget, gender, preferences, email FROM roommates WHERE email<>? ORDER BY id DESC LIMIT 3", "s", [$user_email]);
}

/* ---------- activity ---------- */
$bookings = db_all($conn, "SELECT pg_name, city, rent, booking_date, status FROM bookings WHERE user_email=? ORDER BY booking_date DESC LIMIT 3", "s", [$user_email]);
$pending  = db_all(
    $conn,
    "SELECT rr.id, rr.sender_email, rr.request_date,
            COALESCE((SELECT fullname FROM roommates WHERE email = rr.sender_email LIMIT 1), rr.sender_email) AS sender_name
     FROM roommate_requests rr
     WHERE rr.receiver_email=? AND rr.status='Pending'
     ORDER BY rr.request_date DESC LIMIT 3",
    "s",
    [$user_email]
);

$hour     = (int)date('G');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');

function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Roommate & PG Finder</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dx-wrap { max-width: 1120px; margin: 0 auto; padding: 28px 20px 48px; text-align: left; }
        .dx-hero { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: space-between;
                   background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); color: #fff;
                   border-radius: 18px; padding: 28px 30px; box-shadow: 0 12px 30px rgba(37,99,235,.25); }
        .dx-hero h1 { margin: 0 0 6px; font-size: 28px; color: #fff; text-align: left; }
        .dx-hero p  { margin: 0; color: #DBEAFE; font-size: 15px; }
        .dx-chips { margin-top: 12px; display: flex; flex-wrap: wrap; gap: 8px; }
        .dx-chip { background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.25); padding: 5px 12px;
                   border-radius: 999px; font-size: 13px; }
        .dx-actions { display: flex; flex-wrap: wrap; gap: 10px; }
        .dx-btn { display: inline-block; padding: 11px 20px; border-radius: 10px; font-weight: 700; font-size: 14px;
                  text-decoration: none; border: 2px solid transparent; }
        .dx-btn-light { background: #fff; color: #1D4ED8; }
        .dx-btn-ghost { background: transparent; color: #fff; border-color: rgba(255,255,255,.55); }
        .dx-btn-primary { background: #2563EB; color: #fff; }
        .dx-btn:hover { transform: translateY(-1px); }

        .dx-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; margin: 22px 0; }
        .dx-stat { display: block; background: #fff; border: 1px solid #E2E8F0; border-radius: 14px; padding: 18px 20px;
                   text-decoration: none; color: #1E293B; box-shadow: 0 2px 8px rgba(15,23,42,.05);
                   transition: box-shadow .2s, transform .2s; }
        .dx-stat:hover { box-shadow: 0 10px 22px rgba(15,23,42,.10); transform: translateY(-2px); }
        .dx-stat .ico { font-size: 22px; }
        .dx-stat .num { font-size: 32px; font-weight: 800; margin: 6px 0 0; color: #0F172A; line-height: 1.1; }
        .dx-stat .lbl { font-size: 13px; color: #64748B; font-weight: 600; }
        .dx-stat .sub { font-size: 12px; color: #16A34A; font-weight: 700; margin-top: 4px; min-height: 16px; }

        .dx-callout { display: flex; flex-wrap: wrap; gap: 14px; align-items: center; justify-content: space-between;
                      background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 14px; padding: 16px 20px; margin-bottom: 22px; }
        .dx-callout strong { color: #92400E; }
        .dx-callout span { color: #78350F; font-size: 14px; }

        .dx-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 22px; align-items: start; }
        .dx-panel { background: #fff; border: 1px solid #E2E8F0; border-radius: 16px; padding: 22px; margin-bottom: 22px;
                    box-shadow: 0 2px 8px rgba(15,23,42,.04); }
        .dx-panel h2 { margin: 0 0 14px; font-size: 18px; color: #0F172A; display: flex; justify-content: space-between; align-items: baseline; }
        .dx-panel h2 a { font-size: 13px; font-weight: 700; color: #2563EB; text-decoration: none; }

        .dx-pgs { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 14px; }
        .dx-pg { display: block; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; text-decoration: none; color: inherit;
                 background: #fff; transition: box-shadow .2s, transform .2s; }
        .dx-pg:hover { box-shadow: 0 10px 22px rgba(15,23,42,.10); transform: translateY(-2px); }
        .dx-pg img { width: 100%; aspect-ratio: 16/10; object-fit: cover; display: block; background: #E2E8F0; }
        .dx-pg .body { padding: 12px 14px 14px; }
        .dx-pg .name { font-weight: 700; color: #0F172A; font-size: 15px; margin-bottom: 4px; }
        .dx-pg .meta { font-size: 12px; color: #64748B; }
        .dx-pg .price { margin-top: 8px; font-weight: 800; color: #2563EB; }
        .dx-pg .price small { color: #64748B; font-weight: 600; }
        .dx-pg .rate { float: right; font-size: 12px; font-weight: 700; color: #B45309; }

        .dx-list { list-style: none; margin: 0; padding: 0; }
        .dx-list li { display: flex; justify-content: space-between; gap: 10px; padding: 11px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; }
        .dx-list li:last-child { border-bottom: 0; }
        .dx-list .t { font-weight: 700; color: #1E293B; }
        .dx-list .s { color: #64748B; font-size: 12px; }
        .dx-pill { align-self: center; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 999px; background: #DCFCE7; color: #15803D; white-space: nowrap; }
        .dx-pill.warn { background: #FEF3C7; color: #B45309; }
        .dx-empty { color: #64748B; font-size: 14px; margin: 0; }
        .dx-empty a { color: #2563EB; font-weight: 700; text-decoration: none; }

        .dx-mates { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; }
        .dx-mate { border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; background: #fff; }
        .dx-mate .top { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
        .dx-avatar { width: 42px; height: 42px; border-radius: 50%; background: #DBEAFE; color: #1D4ED8; display: flex;
                     align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0; }
        .dx-mate .nm { font-weight: 700; color: #0F172A; }
        .dx-mate .sm { font-size: 12px; color: #64748B; }
        .dx-mate p { margin: 0; font-size: 13px; color: #475569; font-style: italic;
                     display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .dx-sample { display: inline-block; margin-left: 6px; font-size: 10px; font-weight: 800; letter-spacing: .3px; text-transform: uppercase;
                     background: #F1F5F9; color: #64748B; border-radius: 6px; padding: 2px 6px; vertical-align: middle; }

        @media (max-width: 860px) {
            .dx-grid { grid-template-columns: 1fr; }
            .dx-hero { padding: 22px 20px; }
            .dx-hero h1 { font-size: 23px; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <h2>Roommate & PG Finder</h2>
    <button class="mobile-nav-toggle" id="navToggle" type="button" aria-label="Toggle menu" aria-expanded="false">&#9776;</button>
    <div class="menu">
        <a href="home.html">Home</a>
        <a href="search.php">Search PG</a>
        <a href="roommates.php">Find Roommate</a>
        <a href="dashboard.php" class="active-nav">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<main class="dx-wrap">

    <!-- Hero -->
    <section class="dx-hero">
        <div>
            <h1><?= e($greeting) ?>, <?= e($firstName) ?> 👋</h1>
            <p>Here's what's happening with your PG search and roommate requests.</p>
            <div class="dx-chips">
                <?php if ($city !== ''): ?><span class="dx-chip">📍 <?= e($city) ?></span><?php endif; ?>
                <?php if ($budget > 0): ?><span class="dx-chip">💰 Budget ₹<?= number_format($budget) ?>/mo</span><?php endif; ?>
                <?php if (!empty($user['gender'])): ?><span class="dx-chip">👤 <?= e($user['gender']) ?></span><?php endif; ?>
            </div>
        </div>
        <div class="dx-actions">
            <a class="dx-btn dx-btn-light" href="search.php">🔍 Search PGs</a>
            <a class="dx-btn dx-btn-ghost" href="roommates.php">👥 Find Roommates</a>
            <a class="dx-btn dx-btn-ghost" href="account-settings.php">⚙️ Account</a>
        </div>
    </section>

    <!-- Stats (each card is a link) -->
    <section class="dx-stats">
        <a class="dx-stat" href="booking-history.php">
            <div class="ico">📖</div>
            <div class="num"><?= $booking_count ?></div>
            <div class="lbl">My Bookings</div>
            <div class="sub">&nbsp;</div>
        </a>
        <a class="dx-stat" href="saved_pgs.php">
            <div class="ico">❤️</div>
            <div class="num"><?= $saved_count ?></div>
            <div class="lbl">Saved PGs</div>
            <div class="sub">&nbsp;</div>
        </a>
        <a class="dx-stat" href="roommate-requests.php">
            <div class="ico">📨</div>
            <div class="num"><?= $pending_count ?></div>
            <div class="lbl">Pending Requests</div>
            <div class="sub"><?= $pending_count > 0 ? 'Needs your response' : '&nbsp;' ?></div>
        </a>
        <a class="dx-stat" href="roommate-requests.php">
            <div class="ico">🤝</div>
            <div class="num"><?= $accepted_count ?></div>
            <div class="lbl">Roommate Connections</div>
            <div class="sub">&nbsp;</div>
        </a>
    </section>

    <?php if (!$has_profile): ?>
    <section class="dx-callout">
        <div>
            <strong>Create your roommate profile</strong><br>
            <span>Other students can only find you once you've added a short profile.</span>
        </div>
        <a class="dx-btn dx-btn-primary" href="find-roommate.php">Create Profile</a>
    </section>
    <?php endif; ?>

    <div class="dx-grid">

        <!-- Left column -->
        <div>
            <section class="dx-panel">
                <h2><?= e($recsTitle) ?> <a href="search.php">View all →</a></h2>
                <?php if ($recs): ?>
                <div class="dx-pgs">
                    <?php foreach ($recs as $pg): ?>
                    <a class="dx-pg" href="pg_details.php?id=<?= (int)$pg['id'] ?>">
                        <img src="<?= e($pg['image1'] ?: 'Images/pgs/pg_bed_1.jpg') ?>" alt="<?= e($pg['pg_name']) ?>" loading="lazy">
                        <div class="body">
                            <span class="rate">★ <?= number_format((float)$pg['rating'], 1) ?></span>
                            <div class="name"><?= e($pg['pg_name']) ?></div>
                            <div class="meta"><?= e($pg['city']) ?> · <?= e($pg['sharing']) ?></div>
                            <div class="price">₹<?= number_format((int)$pg['rent']) ?> <small>/ month</small></div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <p class="dx-empty">No PGs available right now. <a href="search.php">Try searching</a>.</p>
                <?php endif; ?>
            </section>

            <section class="dx-panel">
                <h2>Roommates you might like <a href="roommates.php">See all →</a></h2>
                <?php if ($mates): ?>
                <div class="dx-mates">
                    <?php foreach ($mates as $m): ?>
                    <div class="dx-mate">
                        <div class="top">
                            <div class="dx-avatar"><?= e(strtoupper(substr(trim($m['fullname']), 0, 1))) ?></div>
                            <div>
                                <div class="nm"><?= e($m['fullname']) ?><?php if (is_sample_email($m['email'])): ?><span class="dx-sample">Sample</span><?php endif; ?></div>
                                <div class="sm"><?= e($m['city']) ?> · ₹<?= number_format((int)$m['budget']) ?> · <?= e($m['gender']) ?></div>
                            </div>
                        </div>
                        <p><?= e($m['preferences']) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <p class="dx-empty">No roommate profiles yet. <a href="find-roommate.php">Be the first to create one</a>.</p>
                <?php endif; ?>
            </section>
        </div>

        <!-- Right column -->
        <div>
            <section class="dx-panel">
                <h2>Roommate requests <a href="roommate-requests.php">Manage →</a></h2>
                <?php if ($pending): ?>
                <ul class="dx-list">
                    <?php foreach ($pending as $r): ?>
                    <li>
                        <div>
                            <div class="t"><?= e($r['sender_name']) ?></div>
                            <div class="s">Sent <?= e(date('d M Y', strtotime($r['request_date']))) ?></div>
                        </div>
                        <span class="dx-pill warn">Pending</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                    <p class="dx-empty">No pending requests.</p>
                <?php endif; ?>
            </section>

            <section class="dx-panel">
                <h2>Recent bookings <a href="booking-history.php">History →</a></h2>
                <?php if ($bookings): ?>
                <ul class="dx-list">
                    <?php foreach ($bookings as $b): ?>
                    <li>
                        <div>
                            <div class="t"><?= e($b['pg_name']) ?></div>
                            <div class="s"><?= e($b['city']) ?> · ₹<?= number_format((int)$b['rent']) ?> · <?= e(date('d M Y', strtotime($b['booking_date']))) ?></div>
                        </div>
                        <span class="dx-pill"><?= e($b['status'] ?: 'Confirmed') ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                    <p class="dx-empty">No bookings yet. <a href="search.php">Browse PGs</a> to get started.</p>
                <?php endif; ?>
            </section>
        </div>

    </div>
</main>

<script>
    // Mobile menu: the stylesheet hides .menu under 768px until it gets the "show" class
    (function () {
        var btn = document.getElementById('navToggle');
        var menu = document.querySelector('.navbar .menu');
        if (!btn || !menu) return;
        btn.addEventListener('click', function () {
            var open = menu.classList.toggle('show');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    })();
</script>
</body>
</html>