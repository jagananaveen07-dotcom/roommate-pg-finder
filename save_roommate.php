<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: find-roommate.php");
    exit();
}

// Identity comes from the login session, NOT from the form
// (a "readonly" input can still be edited or faked by anyone).
$fullname = $_SESSION['fullname'];
$email    = $_SESSION['email'];

$city        = trim($_POST['city'] ?? '');
$budget      = (int)($_POST['budget'] ?? 0);
$gender      = trim($_POST['gender'] ?? '');
$preferences = trim($_POST['preferences'] ?? '');

// Validation
$error = '';
if ($city === '' || strlen($city) > 50) {
    $error = 'Please select a valid city.';
} elseif ($budget <= 0 || $budget > 1000000) {
    $error = 'Please enter a valid monthly budget.';
} elseif (!in_array($gender, ['Male', 'Female', 'Other'], true)) {
    $error = 'Please select a valid gender.';
} elseif ($preferences === '' || strlen($preferences) > 1000) {
    $error = 'Please describe yourself in up to 1000 characters.';
}

if ($error !== '') {
    echo "<script>
    alert(" . json_encode($error) . ");
    window.location='find-roommate.php';
    </script>";
    exit();
}

// Already has a profile?
$check = mysqli_prepare($conn, "SELECT id FROM roommates WHERE email=? LIMIT 1");
mysqli_stmt_bind_param($check, "s", $email);
mysqli_stmt_execute($check);
$exists = mysqli_num_rows(mysqli_stmt_get_result($check)) > 0;
mysqli_stmt_close($check);

if ($exists) {
    echo "<script>
    alert('You have already created a roommate profile!');
    window.location='roommates.php';
    </script>";
    exit();
}

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO roommates (fullname, email, city, budget, gender, preferences)
     VALUES (?, ?, ?, ?, ?, ?)"
);
mysqli_stmt_bind_param($stmt, "sssiss", $fullname, $email, $city, $budget, $gender, $preferences);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>
    alert('Roommate Profile Created Successfully!');
    window.location='roommates.php';
    </script>";
} else {
    error_log('save_roommate failed: ' . mysqli_error($conn));
    echo "<script>
    alert('Something went wrong. Please try again.');
    window.location='find-roommate.php';
    </script>";
}
mysqli_stmt_close($stmt);