
<?php
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$adminPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' | ' : '' ?>Admin - ZIPS Chau E-Library</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <a href="dashboard.php" class="sidebar-logo">
                    <i class="fas fa-book-reader"></i>
                    <span>ZIPS Admin</span>
                </a>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="dashboard.php" class="<?= $adminPage === 'dashboard' ? 'active' : '' ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="resources.php" class="<?= $adminPage === 'resources' ? 'active' : '' ?>">
                            <i class="fas fa-folder"></i>
                            <span>Resources</span>
                        </a>
                    </li>
                    <li>
                        <a href="upload.php" class="<?= $adminPage === 'upload' ? 'active' : '' ?>">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Upload</span>
                        </a>
                    </li>
                    <li>
                        <a href="graduates.php" class="<?= $adminPage === 'graduates' ? 'active' : '' ?>">
                            <i class="fas fa-user-graduate"></i>
                            <span>Graduates</span>
                        </a>
                    </li>
                    <li>
                        <a href="add-graduate.php" class="<?= $adminPage === 'add-graduate' ? 'active' : '' ?>">
                            <i class="fas fa-user-plus"></i>
                            <span>Add Graduate</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-divider"></div>

                <ul>
                    <li>
                        <a href="../index.php" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                            <span>View Site</span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <header class="admin-header">
                <div class="admin-header-left">
                    <button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="admin-page-title"><?= $pageTitle ?? 'Admin' ?></h1>
                </div>
                <div class="admin-header-right">
                    <span class="admin-user">
                        <i class="fas fa-user-circle"></i>
                        <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>
                    </span>
                </div>
            </header>

            <main class="admin-content">
