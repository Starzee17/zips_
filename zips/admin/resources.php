
<?php
/**
 * ZIPS Chau E-Library - Admin Resources Management
 */
$pageTitle = 'Manage Resources';
require_once '../includes/functions.php';
requireAdmin();

$message = '';
$error = '';

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("UPDATE resources SET is_active = 0 WHERE id = ?");
            $stmt->execute([$id]);
            $message = 'Resource deleted successfully.';
        } catch (PDOException $e) {
            $error = 'Failed to delete resource.';
        }
    }
}

// Get resources
$type = isset($_GET['type']) ? sanitize($_GET['type']) : null;
$search = isset($_GET['search']) ? sanitize($_GET['search']) : null;
$resources = getResources($type, null, $search);
?>
<?php include 'includes/admin-header.php'; ?>

<?php if ($message): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="admin-filters">
    <div class="filter-tabs">
        <a href="resources.php" class="filter-tab <?= !$type ? 'active' : '' ?>">All</a>
        <a href="resources.php?type=module" class="filter-tab <?= $type === 'module' ? 'active' : '' ?>">Modules</a>
        <a href="resources.php?type=picture" class="filter-tab <?= $type === 'picture' ? 'active' : '' ?>">Pictures</a>
        <a href="resources.php?type=video" class="filter-tab <?= $type === 'video' ? 'active' : '' ?>">Videos</a>
        <a href="resources.php?type=article" class="filter-tab <?= $type === 'article' ? 'active' : '' ?>">Articles</a>
    </div>

    <form action="" method="GET" class="admin-search">
        <?php if ($type): ?>
        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
        <?php endif; ?>
        <input type="text" name="search" placeholder="Search resources..." value="<?= htmlspecialchars($search ?? '') ?>">
        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
    </form>

    <a href="upload.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Resource
    </a>
</div>

<!-- Resources Table -->
<div class="admin-card">
    <?php if (empty($resources)): ?>
    <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <h3>No Resources Found</h3>
        <p>No resources match your criteria.</p>
        <a href="upload.php" class="btn btn-primary">Upload First Resource</a>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>File</th>
                    <th>Size</th>
                    <th>Views</th>
                    <th>Uploaded</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resources as $resource): ?>
                <tr>
                    <td><?= $resource['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars(substr($resource['title'], 0, 40)) ?><?= strlen($resource['title']) > 40 ? '...' : '' ?></strong>
                    </td>
                    <td>
                        <span class="badge <?= getTypeBadgeClass($resource['file_type']) ?>">
                            <?= ucfirst($resource['file_type']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="file-name">
                            <i class="fas <?= getFileIcon($resource['file_name']) ?>"></i>
                            <?= htmlspecialchars(substr($resource['file_name'], 0, 20)) ?>...
                        </span>
                    </td>
                    <td><?= formatFileSize($resource['file_size']) ?></td>
                    <td><?= number_format($resource['views']) ?></td>
                    <td><?= date('M j, Y', strtotime($resource['upload_date'])) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="../view.php?id=<?= $resource['id'] ?>"
                               target="_blank"
                               class="btn btn-sm btn-outline"
                               title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-outline copy-link"
                                    data-token="<?= htmlspecialchars($resource['share_token']) ?>"
                                    title="Copy Share Link">
                                <i class="fas fa-link"></i>
                            </button>
                            <a href="?delete=<?= $resource['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this resource?')"
                               title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/admin-footer.php'; ?>
