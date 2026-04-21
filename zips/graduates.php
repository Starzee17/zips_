
<?php
/**
 * ZIPS Chau E-Library - Graduates Page
 */
$pageTitle = 'Our Graduates';
require_once 'includes/functions.php';
requireAuth();

$year = isset($_GET['year']) ? (int)$_GET['year'] : null;
$graduates = getGraduates($year);

// Get unique years for filter
$allGraduates = getGraduates();
$years = array_unique(array_column($allGraduates, 'graduation_year'));
rsort($years);
?>
<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header graduates-header">
    <div class="container">
        <div class="page-header-content">
            <h1>Our Graduates</h1>
            <p>Celebrating the achievements of ZIPS Chau members who have completed their professional certifications</p>
        </div>
    </div>
</section>

<!-- Graduates Section -->
<section class="graduates-section">
    <div class="container">
        <!-- Year Filter -->
        <div class="graduates-filter">
            <span class="filter-label">Filter by Year:</span>
            <div class="year-tabs">
                <a href="graduates.php" class="year-tab <?= !$year ? 'active' : '' ?>">All Years</a>
                <?php foreach ($years as $y): ?>
                <a href="graduates.php?year=<?= $y ?>" class="year-tab <?= $year === $y ? 'active' : '' ?>"><?= $y ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (empty($graduates)): ?>
        <div class="empty-state">
            <i class="fas fa-user-graduate"></i>
            <h3>No Graduates Found</h3>
            <p>
                <?php if ($year): ?>
                No graduates recorded for <?= $year ?>. Try selecting a different year.
                <?php else: ?>
                No graduates have been added yet.
                <?php endif; ?>
            </p>
            <a href="graduates.php" class="btn btn-primary">View All Graduates</a>
        </div>
        <?php else: ?>

        <!-- Graduates Count -->
        <div class="graduates-count">
            <p>Showing <strong><?= count($graduates) ?></strong> graduate<?= count($graduates) !== 1 ? 's' : '' ?><?= $year ? ' from ' . $year : '' ?></p>
        </div>

        <!-- Graduates Grid -->
        <div class="graduates-grid">
            <?php foreach ($graduates as $graduate): ?>
            <div class="graduate-card">
                <div class="graduate-photo">
                    <?php if ($graduate['photo']): ?>
                    <img src="assets/uploads/graduates/<?= htmlspecialchars($graduate['photo']) ?>"
                         alt="<?= htmlspecialchars($graduate['full_name']) ?>">
                    <?php else: ?>
                    <div class="graduate-avatar">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <?php endif; ?>

                    <?php if ($graduate['honors']): ?>
                    <span class="honors-badge"><?= htmlspecialchars($graduate['honors']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="graduate-info">
                    <h3><?= htmlspecialchars($graduate['full_name']) ?></h3>
                    <p class="student-id">ID: <?= htmlspecialchars($graduate['student_id'] ?? 'N/A') ?></p>
                    <p class="program"><?= htmlspecialchars($graduate['program'] ?? 'N/A') ?></p>
                    <div class="graduate-meta">
                        <span class="graduation-year">
                            <i class="fas fa-calendar-alt"></i>
                            Class of <?= $graduate['graduation_year'] ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="graduates-cta">
    <div class="container">
        <div class="cta-box">
            <h2>Are You a ZIPS Graduate?</h2>
            <p>If you have completed a ZIPS certification and would like to be featured, please contact us.</p>
            <a href="contact.php" class="btn btn-primary btn-lg">
                <i class="fas fa-envelope"></i> Contact Us
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
