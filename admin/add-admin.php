<?php
session_start();

// Only a logged-in admin can create another admin
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

include("../includes/db.php");

// CSRF token for the form
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors   = [];
$success  = "";
$username = "";
$email    = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = "Session expired. Please refresh the page and try again.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $email    = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        // Validation
        if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username)) {
            $errors[] = "Username must be 3-50 characters: letters, numbers, dot, dash or underscore.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
            $errors[] = "Please enter a valid email address (used for password reset).";
        }
        if (strlen($password) < 10) {
            $errors[] = "Password must be at least 10 characters.";
        }
        if (strlen($password) > 72) {
            $errors[] = "Password must be 72 characters or fewer.";   // bcrypt limit
        }
        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
            $errors[] = "Password must contain at least one letter and one number.";
        }
        if ($password !== $confirm) {
            $errors[] = "Passwords do not match.";
        }

        if (empty($errors)) {
            // Duplicate username / email check
            $check = mysqli_prepare($conn, "SELECT id FROM admin WHERE username=? OR email=? LIMIT 1");
            mysqli_stmt_bind_param($check, "ss", $username, $email);
            mysqli_stmt_execute($check);
            $exists = mysqli_num_rows(mysqli_stmt_get_result($check)) > 0;
            mysqli_stmt_close($check);

            if ($exists) {
                $errors[] = "That username or email is already in use.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = mysqli_prepare($conn, "INSERT INTO admin (username, email, password) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);

                if (mysqli_stmt_execute($stmt)) {
                    $success  = "Admin '" . $username . "' created successfully.";
                    $username = "";
                    $email    = "";
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));   // fresh token after success
                } else {
                    $errors[] = "Could not create the admin. Please try again.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin - Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<nav class="navbar">
    <h2>Admin Panel</h2>
    <div class="menu">
        <a href="admin-dashboard.php">Dashboard</a>
        <a href="manage-pgs.php">Manage PGs</a>
        <a href="manage-users.php">Users</a>
        <a href="add-admin.php">Add Admin</a>
        <a href="admin-logout.php">Logout</a>
    </div>
</nav>

<div class="register-container" style="width:480px;max-width:92%;margin:40px auto;text-align:left;padding:35px;">

    <form method="POST" autocomplete="off">
        <h1 style="text-align:center;color:#2563eb;font-size:28px;margin-bottom:8px;">Add New Admin</h1>
        <p style="text-align:center;color:#64748b;margin-bottom:22px;">
            Only existing admins can create new admin accounts.
        </p>

        <?php if ($success): ?>
            <div style="background:#dcfce7;color:#16a34a;padding:12px;border-radius:8px;margin-bottom:18px;border:1px solid #bbf7d0;font-size:14px;">
                ✓ <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div style="background:#fee2e2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:18px;border:1px solid #fecaca;font-size:14px;">
                <?php foreach ($errors as $e): ?>
                    <div>⚠️ <?php echo htmlspecialchars($e); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <label for="username" style="font-weight:bold;font-size:14px;">Username</label>
        <input type="text" id="username" name="username" required minlength="3" maxlength="50"
               autocomplete="off" style="width:100%;box-sizing:border-box;margin:6px 0 16px;"
               value="<?php echo htmlspecialchars($username); ?>">

        <label for="email" style="font-weight:bold;font-size:14px;">Email (for password reset)</label>
        <input type="email" id="email" name="email" required maxlength="100"
               autocomplete="off" style="width:100%;box-sizing:border-box;margin:6px 0 16px;"
               value="<?php echo htmlspecialchars($email); ?>">

        <label for="password" style="font-weight:bold;font-size:14px;">Password</label>
        <input type="password" id="password" name="password" required minlength="10" maxlength="72"
               autocomplete="new-password" style="width:100%;box-sizing:border-box;margin:6px 0 4px;">
        <small style="color:#64748b;display:block;margin-bottom:16px;">
            At least 10 characters, with a letter and a number.
        </small>

        <label for="confirm_password" style="font-weight:bold;font-size:14px;">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="10" maxlength="72"
               autocomplete="new-password" style="width:100%;box-sizing:border-box;margin:6px 0 20px;">

        <button type="submit" style="width:100%;">Create Admin</button>
    </form>

</div>

</body>
</html>