
<?php
/**
 * ZIPS Chau E-Library - Admin Login
 */
require_once '../includes/config.php';

$error = '';

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simple authentication (in production, use database with hashed passwords)
    if ($username === 'admin' && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_login_time'] = time();

        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | ZIPS Chau E-Library</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-login-page">
    <div class="admin-login-container">
        <div class="admin-login-card">
            <div class="admin-login-header">
                <div class="admin-logo">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h1>Admin Portal</h1>
                <p>ZIPS Chau E-Library</p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="" class="admin-login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text"
                               id="username"
                               name="username"
                               placeholder="Enter username"
                               required
                               autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Enter password"
                               required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="admin-login-footer">
                <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Library</a>
            </div>
        </div>
    </div>
</body>
</html>