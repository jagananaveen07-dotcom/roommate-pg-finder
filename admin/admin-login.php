<?php
session_start();

// Already logged in? Go straight to the dashboard
if (isset($_SESSION['admin'])) {
    header("Location: admin-dashboard.php");
    exit();
}

// One-time messages set by other admin pages
$error    = $_SESSION['admin_flash_error']    ?? '';
$success  = $_SESSION['admin_flash_success']  ?? '';
$lastUser = $_SESSION['admin_flash_username'] ?? '';
unset($_SESSION['admin_flash_error'], $_SESSION['admin_flash_success'], $_SESSION['admin_flash_username']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="login-container">

<form action="admin-login-check.php" method="POST">

    <h1>Admin Login</h1>
    <p>Login to manage the system.</p>

    <?php if ($success): ?>
        <div style="background:#dcfce7;color:#16a34a;padding:12px;border-radius:8px;margin-bottom:18px;border:1px solid #bbf7d0;font-size:14px;text-align:left;">
            ✓ <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div role="alert" style="background:#fee2e2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:18px;border:1px solid #fecaca;font-size:14px;text-align:left;">
            ⚠️ <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <label for="username">Username</label>
    <input
        type="text"
        id="username"
        name="username"
        placeholder="Enter Username"
        autocomplete="username"
        value="<?php echo htmlspecialchars($lastUser); ?>"
        required>

    <br><br>

    <label for="password">Password</label>
    <input
        type="password"
        id="password"
        name="password"
        placeholder="Enter Password"
        autocomplete="current-password"
        required>

    <div style="display:flex;justify-content:space-between;align-items:center;max-width:320px;margin:10px auto 18px;font-size:13px;">
        <label style="cursor:pointer;color:#475569;">
            <input type="checkbox" id="togglePw" style="width:auto;margin-right:6px;"> Show password
        </label>
        <a href="admin-forgot-password.php" style="color:#2563EB;text-decoration:none;font-weight:600;">Forgot Password?</a>
    </div>

    <button type="submit">Login</button>

    <p style="margin-top:18px;font-size:13px;">
        <a href="../home.html" style="color:#64748b;text-decoration:none;">&larr; Back to website</a>
    </p>

</form>

</div>

<script>
    document.getElementById('togglePw').addEventListener('change', function () {
        document.getElementById('password').type = this.checked ? 'text' : 'password';
    });
</script>

</body>

</html>