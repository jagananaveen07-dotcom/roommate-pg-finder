<?php

session_start();

include("../includes/db.php");
include("../includes/csrf.php");

if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: manage-users.php");
    exit();
}

verify_csrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Invalid user ID.");
}

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM users WHERE id = ?"
);

if (!$stmt) {
    die("Database error.");
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

header("Location: manage-users.php");
exit();

?>