<?php
session_start();
include("includes/db.php");
include("includes/csrf.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])) {

    verify_csrf();

    $request_id = (int)$_POST['request_id'];

    // Only the RECEIVER of a still-pending request may reject it
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE roommate_requests
         SET status='Rejected'
         WHERE id=? AND receiver_email=? AND status='Pending'"
    );

    mysqli_stmt_bind_param($stmt, "is", $request_id, $user_email);
    mysqli_stmt_execute($stmt);

    $changed = mysqli_stmt_affected_rows($stmt) > 0;

    mysqli_stmt_close($stmt);

    $msg = $changed ? 'Request Rejected!' : 'Request not found or already handled.';

    echo "<script>
    alert('" . $msg . "');
    window.location='roommate-requests.php';
    </script>";

    exit();
}

header("Location: roommate-requests.php");
exit();
?>