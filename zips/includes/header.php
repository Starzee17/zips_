
<?php
require_once __DIR__ . '/functions.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' | ' : '' ?><?= SITE_NAME ?></title>
    <meta name="description" content="<?= SITE_TAGLINE ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="top-bar-left">
                    <span><i class="fas fa-envelope"></i> chau@zips.com</span>
                    <span><i class="fas fa-phone"></i> +260 772842016</span>
                </div>
                <div class="top-bar-right">
                    <a href="https://www.facebook.com/share/1DTrXBEoVL/?mibextid=wwXIfr" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="logo-text">
                        <span class="logo-title">ZIPS Chau</span>
                        <span class="logo-subtitle">E-Library</span>
                    </div>
                </a>

                <nav class="main-nav" id="mainNav">
                    <a href="index.php" class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>">
                        <i class="fas fa-home"></i> Home
                    </a>
                    <a href="library.php" class="nav-link <?= $currentPage === 'library' ? 'active' : '' ?>">
                        <i class="fas fa-book"></i> Library
                    </a>
                    <a href="graduates.php" class="nav-link <?= $currentPage === 'graduates' ? 'active' : '' ?>">
                        <i class="fas fa-user-graduate"></i> Graduates
                    </a>
                    <a href="edu-apps.php" class="nav-link <?= $currentPage === 'edu-apps' ? 'active' : '' ?>">
                        <i class="fas fa-mobile-alt"></i> Free Apps
                    </a>
                    <a href="about.php" class="nav-link <?= $currentPage === 'about' ? 'active' : '' ?>">
                        <i class="fas fa-info-circle"></i> About
                    </a>
                    <a href="contact.php" class="nav-link <?= $currentPage === 'contact' ? 'active' : '' ?>">
                        <i class="fas fa-envelope"></i> Contact
                    </a>
                    <?php if (isAuthenticated()): ?>
                    <a href="logout.php" class="nav-link nav-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <?php endif; ?>
                </nav>

                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Wrapper -->
    <main class="main-content">
