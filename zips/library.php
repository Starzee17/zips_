
<?php
/**
 * ZIPS Chau E-Library - Library Page
 */
$pageTitle = 'Library';
require_once 'includes/functions.php';
requireAuth();

// Get filter parameters
$type = isset($_GET['type']) ? sanitize($_GET['type']) : null;
$search = isset($_GET['search']) ? sanitize($_GET['search']) : null;

// Validate type
$validTypes = ['module', 'picture', 'video', 'article'];
if ($type && !in_array($type, $validTypes)) {
    $type = null;
}

$resources = getResources($type, null, $search);
$stats = getStats();
?>
<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <h1>Resource Library</h1>
            <p>Browse our collection of study materials, videos, articles, and more</p>
        </div>
    </div>
</section>

<!-- Library Content -->
<section class="library-section">
    <div class="container">
        <!-- Filters -->
        <div class="library-filters">
            <div class="filter-tabs">
                <a href="library.php" class="filter-tab <?= !$type ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i> All
                    <span class="count"><?= $stats['total_resources'] ?></span>
                </a>
                <a href="library.php?type=module" class="filter-tab <?= $type === 'module' ? 'active' : '' ?>">
                    <i class="fas fa-book"></i> Modules
                    <span class="count"><?= $stats['total_modules'] ?></span>
                </a>
                <a href="library.php?type=picture" class="filter-tab <?= $type === 'picture' ? 'active' : '' ?>">
                    <i class="fas fa-images"></i> Pictures
                    <span class="count"><?= $stats['total_pictures'] ?></span>
                </a>
                <a href="library.php?type=video" class="filter-tab <?= $type === 'video' ? 'active' : '' ?>">
                    <i class="fas fa-video"></i> Videos
                    <span class="count"><?= $stats['total_videos'] ?></span>
                </a>
                <a href="library.php?type=article" class="filter-tab <?= $type === 'article' ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i> Articles
                    <span class="count"><?= $stats['total_articles'] ?></span>
                </a>
            </div>

            <form action="" method="GET" class="search-form">
                <?php if ($type): ?>
                <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
                <?php endif; ?>
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text"
                           name="search"
                           placeholder="Search resources..."
                           value="<?= htmlspecialchars($search ?? '') ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>

        <!-- Results Info -->
        <?php if ($search): ?>
        <div class="search-results-info">
            <p>Showing results for "<strong><?= htmlspecialchars($search) ?></strong>"</p>
            <a href="library.php<?= $type ? '?type=' . $type : '' ?>" class="clear-search">
                <i class="fas fa-times"></i> Clear search
            </a>
        </div>
        <?php endif; ?>

        <!-- Resources Grid -->
        <?php if (empty($resources)): ?>
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <h3>No Resources Found</h3>
            <p>
                <?php if ($search): ?>
                No resources match your search criteria. Try different keywords.
                <?php else: ?>
                No resources available in this category yet.
                <?php endif; ?>
            </p>
            <a href="library.php" class="btn btn-primary">View All Resources</a>
        </div>
        <?php else: ?>
        <div class="resources-grid">
            <?php foreach ($resources as $resource): ?>
            <div class="resource-card" data-type="<?= $resource['file_type'] ?>">
                <div class="resource-type-badge <?= getTypeBadgeClass($resource['file_type']) ?>">
                    <?= ucfirst($resource['file_type']) ?>
                </div>

                <?php if ($resource['file_type'] === 'picture'): ?>
                <div class="resource-preview">
                    <img src="assets/uploads/pics/<?= htmlspecialchars($resource['file_name']) ?>"
                         alt="<?= htmlspecialchars($resource['title']) ?>"
                         onerror="this.parentElement.innerHTML='<div class=\'resource-icon\'><i class=\'fas fa-image\'></i></div>'">
                </div>
                <?php elseif ($resource['file_type'] === 'video'): ?>
                <div class="resource-preview video-preview">
                    <div class="resource-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                </div>
                <?php else: ?>
                <div class="resource-icon">
                    <i class="fas <?= getFileIcon($resource['file_name']) ?>"></i>
                </div>
                <?php endif; ?>

                <div class="resource-content">
                    <h3><?= htmlspecialchars($resource['title']) ?></h3>
                    <p><?= htmlspecialchars(substr($resource['description'] ?? 'No description available.', 0, 100)) ?><?= strlen($resource['description'] ?? '') > 100 ? '...' : '' ?></p>

                    <div class="resource-meta">
                        <span><i class="fas fa-eye"></i> <?= number_format($resource['views']) ?></span>
                        <span><i class="fas fa-hdd"></i> <?= formatFileSize($resource['file_size']) ?></span>
                        <span><i class="fas fa-clock"></i> <?= timeAgo($resource['upload_date']) ?></span>
                    </div>
                </div>

                <div class="resource-actions">
                    <a href="view.php?id=<?= $resource['id'] ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <button class="btn btn-outline btn-sm copy-share-link"
                            data-token="<?= htmlspecialchars($resource['share_token']) ?>"
                            title="Copy share link">
                        <i class="fas fa-share-alt"></i> Share
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
