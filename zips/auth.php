
<?php
/**
 * ZIPS Chau E-Library - PIN Authentication
 */
require_once 'includes/config.php';

$error = '';
$success = '';

// Check if already authenticated
if (isset($_SESSION['library_access']) && $_SESSION['library_access'] === true) {
    header('Location: index.php');
    exit;
}

// Handle PIN submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = $_POST['pin'] ?? '';

    if ($pin === LIBRARY_PIN) {
        $_SESSION['library_access'] = true;
        $_SESSION['access_time'] = time();

        // Log successful access
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO access_logs (ip_address, user_agent, page_accessed, success) VALUES (?, ?, 'auth', 1)");
                $stmt->execute([$_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? '']);
            } catch (PDOException $e) {
                // Silently fail
            }
        }

        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid PIN. Please try again.';

        // Log failed attempt
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO access_logs (ip_address, user_agent, page_accessed, success) VALUES (?, ?, 'auth', 0)");
                $stmt->execute([$_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? '']);
            } catch (PDOException $e) {
                // Silently fail
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
    <title>Access Library | ZIPS Chau E-Library</title>
    <meta name="description" content="Enter your PIN to access the ZIPS Chau E-Library resources.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h1>ZIPS Chau E-Library</h1>
                <p>Zambia Institute of Purchasing and Supply<br>Chalimbana University Chapter</p>
            </div>

            <div class="auth-body">
                <h2>Enter Access PIN</h2>
                <p class="auth-instruction">Please enter your PIN to access the library resources.</p>

                <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="" class="auth-form">
                    <div class="form-group">
                        <label for="pin">Access PIN</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password"
                                   id="pin"
                                   name="pin"
                                   placeholder="Enter your PIN"
                                   required
                                   autofocus
                                   autocomplete="off">
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-unlock"></i> Access Library
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Don't have a PIN? Contact the chapter administrator.</p>
                    <a href="contact.php" class="link">Request Access</a>
                </div>
            </div>
        </div>

        <div class="auth-info">
            <h3>About ZIPS Chau Chapter</h3>
            <p>Established in July 2023, we are dedicated to linking procurement theory to practical skills for students at Chalimbana University.</p>
            <ul>
                <li><i class="fas fa-check"></i> Access study modules and resources</li>
                <li><i class="fas fa-check"></i> Watch educational video content</li>
                <li><i class="fas fa-check"></i> Browse articles and publications</li>
                <li><i class="fas fa-check"></i> Connect with fellow graduates</li>
            </ul>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pinInput = document.getElementById('pin');
            const toggleIcon = document.getElementById('toggleIcon');

            if (pinInput.type === 'password') {
                pinInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                pinInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
