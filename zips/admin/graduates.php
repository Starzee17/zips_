
<?php
/**
 * ZIPS Chau E-Library - Admin Graduates Management
 */
$pageTitle = 'Manage Graduates';
require_once '../includes/functions.php';
requireAdmin();

$message = '';
$error = '';

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    $pdo = getDBConnection();
    if ($pdo) {
        try {
            if ($action === 'publish') {
                $stmt = $pdo->prepare("UPDATE graduates SET is_published = 1 WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Graduate published successfully.';
            } elseif ($action === 'unpublish') {
                $stmt = $pdo->prepare("UPDATE graduates SET is_published = 0 WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Graduate unpublished successfully.';
            } elseif ($action === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM graduates WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Graduate deleted successfully.';
            }
        } catch (PDOException $e) {
            $error = 'Action failed: ' . $e->getMessage();
        }
    }
}

// Get graduates
$year = isset($_GET['year']) ? (int)$_GET['year'] : null;
$graduates = getGraduates($year, false); // Get all, including unpublished
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
        <a href="graduates.php" class="filter-tab <?= !$year ? 'active' : '' ?>">All Years</a>
        <a href="graduates.php?year=2024" class="filter-tab <?= $year === 2024 ? 'active' : '' ?>">2024</a>
        <a href="graduates.php?year=2023" class="filter-tab <?= $year === 2023 ? 'active' : '' ?>">2023</a>
    </div>

    <a href="add-graduate.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Graduate
    </a>
</div>

<!-- Graduates Table -->
<div class="admin-card">
    <?php if (empty($graduates)): ?>
    <div class="empty-state">
        <i class="fas fa-user-graduate"></i>
        <h3>No Graduates Found</h3>
        <p>No graduates have been added yet.</p>
        <a href="add-graduate.php" class="btn btn-primary">Add First Graduate</a>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Student ID</th>
                    <th>Program</th>
                    <th>Year</th>
                    <th>Honors</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($graduates as $graduate): ?>
                <tr>
                    <td><?= $graduate['id'] ?></td>
                    <td>
                        <div class="table-avatar">
                            <?php if ($graduate['photo']): ?>
                            <img src="../assets/uploads/graduates/<?= htmlspecialchars($graduate['photo']) ?>" alt="">
                            <?php else: ?>
                            <i class="fas fa-user"></i>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><strong><?= htmlspecialchars($graduate['full_name']) ?></strong></td>
                    <td><?= htmlspecialchars($graduate['student_id'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars(substr($graduate['program'] ?? 'N/A', 0, 25)) ?>...</td>
                    <td><?= $graduate['graduation_year'] ?></td>
                    <td>
                        <?php if ($graduate['honors']): ?>
                        <span class="badge badge-gold"><?= htmlspecialchars($graduate['honors']) ?></span>
                        <?php else: ?>
                        <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($graduate['is_published']): ?>
                        <span class="badge badge-success">Published</span>
                        <?php else: ?>
                        <span class="badge badge-warning">Draft</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <?php if ($graduate['is_published']): ?>
                            <a href="?action=unpublish&id=<?= $graduate['id'] ?>"
                               class="btn btn-sm btn-outline"
                               title="Unpublish">
                                <i class="fas fa-eye-slash"></i>
                            </a>
                            <?php else: ?>
                            <a href="?action=publish&id=<?= $graduate['id'] ?>"
                               class="btn btn-sm btn-success"
                               title="Publish">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php endif; ?>
                            <a href="?action=delete&id=<?= $graduate['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this graduate?')"
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
