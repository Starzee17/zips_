
<?php
/**
 * ZIPS Chau E-Library - View Resource
 */
require_once 'includes/functions.php';
requireAuth();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: library.php');
    exit;
}

$resource = getResourceById($id);

if (!$resource) {
    header('Location: library.php');
    exit;
}

// Increment view count
incrementViews($id);
$resource['views']++; // Update local count for display

$pageTitle = $resource['title'];

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

// Build share URL
$shareUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') .
            '://' . $_SERVER['HTTP_HOST'] .
            dirname($_SERVER['REQUEST_URI']) . '/share.php?token=' . $resource['share_token'];
?>
<?php include 'includes/header.php'; ?>

<!-- Breadcrumb -->
<section class="breadcrumb-section">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Home</a>
            <span class="separator"><i class="fas fa-chevron-right"></i></span>
            <a href="library.php">Library</a>
            <span class="separator"><i class="fas fa-chevron-right"></i></span>
            <a href="library.php?type=<?= $resource['file_type'] ?>"><?= ucfirst($resource['file_type']) ?>s</a>
            <span class="separator"><i class="fas fa-chevron-right"></i></span>
            <span class="current"><?= htmlspecialchars($resource['title']) ?></span>
        </nav>
    </div>
</section>

<!-- Resource View -->
<section class="resource-view-section">
    <div class="container">
        <div class="resource-view-grid">
            <!-- Main Content -->
            <div class="resource-main">
                <div class="resource-header">
                    <span class="resource-type-badge <?= getTypeBadgeClass($resource['file_type']) ?>">
                        <?= ucfirst($resource['file_type']) ?>
                    </span>
                    <h1><?= htmlspecialchars($resource['title']) ?></h1>
                </div>

                <!-- Resource Preview -->
                <div class="resource-preview-large">
                    <?php if ($resource['file_type'] === 'picture'): ?>
                        <img src="<?= $filePath ?>"
                             alt="<?= htmlspecialchars($resource['title']) ?>"
                             onerror="this.src='assets/images/placeholder.png'">

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
                            <p>This file type cannot be previewed in browser.</p>
                            <p>Click download to view the file.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div class="resource-description">
                    <h3>Description</h3>
                    <p><?= nl2br(htmlspecialchars($resource['description'] ?? 'No description available.')) ?></p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="resource-sidebar">
                <!-- Info Card -->
                <div class="info-card">
                    <h3>Resource Information</h3>
                    <ul class="info-list">
                        <li>
                            <span class="info-label"><i class="fas fa-file"></i> File Name</span>
                            <span class="info-value"><?= htmlspecialchars($resource['file_name']) ?></span>
                        </li>
                        <li>
                            <span class="info-label"><i class="fas fa-folder"></i> Type</span>
                            <span class="info-value"><?= ucfirst($resource['file_type']) ?></span>
                        </li>
                        <li>
                            <span class="info-label"><i class="fas fa-hdd"></i> Size</span>
                            <span class="info-value"><?= formatFileSize($resource['file_size']) ?></span>
                        </li>
                        <li>
                            <span class="info-label"><i class="fas fa-eye"></i> Views</span>
                            <span class="info-value"><?= number_format($resource['views']) ?></span>
                        </li>
                        <li>
                            <span class="info-label"><i class="fas fa-calendar"></i> Uploaded</span>
                            <span class="info-value"><?= date('M j, Y', strtotime($resource['upload_date'])) ?></span>
                        </li>
                    </ul>
                </div>

                <!-- Actions Card -->
                <div class="actions-card">
                    <h3>Actions</h3>
                    <a href="<?= $filePath ?>" download class="btn btn-primary btn-block">
                        <i class="fas fa-download"></i> Download File
                    </a>
                    <button class="btn btn-outline btn-block copy-share-link" data-url="<?= htmlspecialchars($shareUrl) ?>">
                        <i class="fas fa-share-alt"></i> Copy Share Link
                    </button>
                    <a href="library.php?type=<?= $resource['file_type'] ?>" class="btn btn-ghost btn-block">
                        <i class="fas fa-arrow-left"></i> Back to Library
                    </a>
                </div>

                <!-- Share Card -->
                <div class="share-card">
                    <h3>Share This Resource</h3>
                    <p>Share this resource with others using the link below:</p>
                    <div class="share-link-box">
                        <input type="text" value="<?= htmlspecialchars($shareUrl) ?>" readonly id="shareLink">
                        <button class="btn btn-sm btn-primary" onclick="copyShareLink()">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <p class="share-note">Note: Anyone with this link can view the resource without logging in.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function copyShareLink() {
    const shareLink = document.getElementById('shareLink');
    shareLink.select();
    document.execCommand('copy');

    // Show feedback
    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i>';
    btn.classList.add('btn-success');

    setTimeout(() => {
        btn.innerHTML = originalHtml;
        btn.classList.remove('btn-success');
    }, 2000);
}
</script>

<?php include 'includes/footer.php'; ?>
