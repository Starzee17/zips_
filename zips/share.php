
<?php
/**
 * ZIPS Chau E-Library - Public Share Page
 * No authentication required
 */
require_once 'includes/functions.php';

$token = isset($_GET['token']) ? sanitize($_GET['token']) : '';

if (!$token) {
    header('Location: auth.php');
    exit;
}

$resource = getResourceByToken($token);

if (!$resource) {
    $pageTitle = 'Resource Not Found';
    $notFound = true;
} else {
    $pageTitle = $resource['title'];
    $notFound = false;

    // Increment view count
    incrementViews($resource['id']);
    $resource['views']++;

    // Determine file path
    $uploadDirs = [
        'module' => 'modules',
        'picture' => 'pics',
        'video' => 'videos',
        'article' => 'articles'
    ];
    $fileDir = $uploadDirs[$resource['file_type']] ?? 'modules';
    $filePath = "assets/uploads/{$fileDir}/" . $resource['file_name'];
    $fileExt = strtolower(pathinfo($resource['file_name'], PATHINFO_EXTENSION));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | ZIPS Chau E-Library</title>
    <meta name="description" content="<?= $notFound ? 'Resource not found' : htmlspecialchars($resource['description'] ?? '') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="share-page">
    <!-- Simple Header -->
    <header class="share-header">
        <div class="container">
            <a href="auth.php" class="logo">
                <i class="fas fa-book-reader"></i>
                <span>ZIPS Chau E-Library</span>
            </a>
        </div>
    </header>

    <main class="share-main">
        <div class="container">
            <?php if ($notFound): ?>
            <div class="share-not-found">
                <i class="fas fa-exclamation-triangle"></i>
                <h1>Resource Not Found</h1>
                <p>The resource you are looking for may have been removed or the link is invalid.</p>
                <a href="auth.php" class="btn btn-primary">Go to Library</a>
            </div>
            <?php else: ?>
            <div class="share-content">
                <div class="share-resource-header">
                    <span class="resource-type-badge <?= getTypeBadgeClass($resource['file_type']) ?>">
                        <?= ucfirst($resource['file_type']) ?>
                    </span>
                    <h1><?= htmlspecialchars($resource['title']) ?></h1>
                    <p class="share-meta">
                        <span><i class="fas fa-eye"></i> <?= number_format($resource['views']) ?> views</span>
                        <span><i class="fas fa-calendar"></i> <?= date('M j, Y', strtotime($resource['upload_date'])) ?></span>
                        <span><i class="fas fa-hdd"></i> <?= formatFileSize($resource['file_size']) ?></span>
                    </p>
                </div>

                <div class="share-preview">
                    <?php if ($resource['file_type'] === 'picture'): ?>
                        <img src="<?= $filePath ?>" alt="<?= htmlspecialchars($resource['title']) ?>">

                    <?php elseif ($resource['file_type'] === 'video'): ?>
                        <div class="video-container">
                            <video controls>
                                <source src="<?= $filePath ?>" type="video/<?= $fileExt ?>">
                                Your browser does not support the video tag.
                            </video>
                        </div>

                    <?php elseif ($fileExt === 'pdf'): ?>
                        <div class="pdf-container">
                            <iframe src="<?= $filePath ?>" width="100%" height="600px"></iframe>
                        </div>

                    <?php else: ?>
                        <div class="file-preview">
                            <div class="file-icon-large">
                                <i class="fas <?= getFileIcon($resource['file_name']) ?>"></i>
                            </div>
                            <p><?= htmlspecialchars($resource['file_name']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($resource['description']): ?>
                <div class="share-description">
                    <h3>Description</h3>
                    <p><?= nl2br(htmlspecialchars($resource['description'])) ?></p>
                </div>
                <?php endif; ?>

                <div class="share-actions">
                    <a href="<?= $filePath ?>" download class="btn btn-primary btn-lg">
                        <i class="fas fa-download"></i> Download File
                    </a>
                    <a href="auth.php" class="btn btn-outline btn-lg">
                        <i class="fas fa-book-reader"></i> Access Full Library
                    </a>
                </div>

                <div class="share-info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>This resource is shared from the ZIPS Chau E-Library. Enter the access PIN to browse all available resources.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="share-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> ZIPS Chau Chapter | Zambia Institute of Purchasing and Supply</p>
        </div>
    </footer>
</body>
</html>
