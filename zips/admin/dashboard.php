
<?php
/**
 * ZIPS Chau E-Library - Admin Dashboard
 */
$pageTitle = 'Dashboard';
require_once '../includes/functions.php';
requireAdmin();

$stats = getStats();
$recentResources = getResources(null, 5);
$recentGraduates = getGraduates(null, false);
$recentGraduates = array_slice($recentGraduates, 0, 5);
?>
<?php include 'includes/admin-header.php'; ?>


<link rel="stylesheet" href="assets/css/dashboard.css">
<!-- Dashboard Stats -->
<div class="dashboard-stats">
    <div class="stat-card stat-primary">
        <div class="stat-icon">
            <i class="fas fa-folder"></i>
        </div>
        <div class="stat-info">
            <h3><?= number_format($stats['total_resources']) ?></h3>
            <p>Total Resources</p>
        </div>
    </div>

    <div class="stat-card stat-blue">
        <div class="stat-icon">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-info">
            <h3><?= number_format($stats['total_modules']) ?></h3>
            <p>Modules</p>
        </div>
    </div>

    <div class="stat-card stat-green">
        <div class="stat-icon">
            <i class="fas fa-images"></i>
        </div>
        <div class="stat-info">
            <h3><?= number_format($stats['total_pictures']) ?></h3>
            <p>Pictures</p>
        </div>
    </div>

    <div class="stat-card stat-red">
        <div class="stat-icon">
            <i class="fas fa-video"></i>
        </div>
        <div class="stat-info">
            <h3><?= number_format($stats['total_videos']) ?></h3>
            <p>Videos</p>
        </div>
    </div>

    <div class="stat-card stat-purple">
        <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <h3><?= number_format($stats['total_articles']) ?></h3>
            <p>Articles</p>
        </div>
    </div>

    <div class="stat-card stat-orange">
        <div class="stat-icon">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-info">
            <h3><?= number_format($stats['total_graduates']) ?></h3>
            <p>Graduates</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <h2>Quick Actions</h2>
    <div class="action-buttons">
        <a href="upload.php" class="action-btn">
            <i class="fas fa-cloud-upload-alt"></i>
            <span>Upload Resource</span>
        </a>
        <a href="add-graduate.php" class="action-btn">
            <i class="fas fa-user-plus"></i>
            <span>Add Graduate</span>
        </a>
        <a href="resources.php" class="action-btn">
            <i class="fas fa-folder-open"></i>
            <span>Manage Resources</span>
        </a>
        <a href="graduates.php" class="action-btn">
            <i class="fas fa-users"></i>
            <span>Manage Graduates</span>
        </a>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Recent Resources -->
    <div class="dashboard-card">
        <div class="card-header">
            <h2>Recent Resources</h2>
            <a href="resources.php" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentResources)): ?>
            <p class="empty-message">No resources uploaded yet.</p>
            <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Views</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentResources as $resource): ?>
                    <tr>
                        <td><?= htmlspecialchars(substr($resource['title'], 0, 30)) ?><?= strlen($resource['title']) > 30 ? '...' : '' ?></td>
                        <td><span class="badge <?= getTypeBadgeClass($resource['file_type']) ?>"><?= ucfirst($resource['file_type']) ?></span></td>
                        <td><?= number_format($resource['views']) ?></td>
                        <td><?= date('M j, Y', strtotime($resource['upload_date'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Graduates -->
    <div class="dashboard-card">
        <div class="card-header">
            <h2>Recent Graduates</h2>
            <a href="graduates.php" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentGraduates)): ?>
            <p class="empty-message">No graduates added yet.</p>
            <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Year</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentGraduates as $graduate): ?>
                    <tr>
                        <td><?= htmlspecialchars($graduate['full_name']) ?></td>
                        <td><?= htmlspecialchars(substr($graduate['program'] ?? 'N/A', 0, 20)) ?>...</td>
                        <td><?= $graduate['graduation_year'] ?></td>
                        <td>
                            <?php if ($graduate['is_published']): ?>
                            <span class="badge badge-success">Published</span>
                            <?php else: ?>
                            <span class="badge badge-warning">Draft</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Activity Stats -->
<div class="dashboard-card full-width">
    <div class="card-header">
        <h2>Library Activity</h2>
    </div>
    <div class="card-body">
        <div class="activity-stats">
            <div class="activity-item">
                <i class="fas fa-eye"></i>
                <div class="activity-info">
                    <h3><?= number_format($stats['total_views']) ?></h3>
                    <p>Total Views</p>
                </div>
            </div>
            <div class="activity-item">
                <i class="fas fa-sign-in-alt"></i>
                <div class="activity-info">
                    <h3><?= number_format($stats['recent_accesses']) ?></h3>
                    <p>Access Logs (7 days)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
