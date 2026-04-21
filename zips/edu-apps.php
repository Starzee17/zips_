
<?php
/**
 * ZIPS Chau E-Library - Educational Apps Page
 */
$pageTitle = 'Free Educational Apps';
require_once 'includes/functions.php';
requireAuth();

$apps = getEduApps();
$categories = getAppCategories();

// Group apps by category
$appsByCategory = [];
foreach ($apps as $app) {
    $cat = $app['category'] ?? 'Other';
    if (!isset($appsByCategory[$cat])) {
        $appsByCategory[$cat] = [];
    }
    $appsByCategory[$cat][] = $app;
}
?>
<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header apps-header">
    <div class="container">
        <div class="page-header-content">
            <h1>Free Educational Apps</h1>
            <p>Discover free online resources and applications to enhance your learning experience</p>
        </div>
    </div>
</section>

<!-- Apps Section -->
<section class="apps-section">
    <div class="container">
        <div class="apps-intro">
            <p>We have compiled a list of free educational applications and websites that can help you in your studies. These resources cover various topics from general learning platforms to specialized research tools.</p>
        </div>

        <?php foreach ($appsByCategory as $category => $categoryApps): ?>
        <div class="apps-category">
            <h2 class="category-title">
                <i class="fas fa-folder"></i>
                <?= htmlspecialchars($category) ?>
                <span class="count"><?= count($categoryApps) ?> app<?= count($categoryApps) !== 1 ? 's' : '' ?></span>
            </h2>

            <div class="apps-grid">
                <?php foreach ($categoryApps as $app): ?>
                <div class="app-card">
                    <div class="app-icon">
                        <?php if (!empty($app['icon_url'])): ?>
                        <img src="<?= htmlspecialchars($app['icon_url']) ?>" alt="<?= htmlspecialchars($app['app_name']) ?>">
                        <?php else: ?>
                        <i class="fas fa-mobile-alt"></i>
                        <?php endif; ?>
                    </div>
                    <div class="app-content">
                        <h3><?= htmlspecialchars($app['app_name']) ?></h3>
                        <p><?= htmlspecialchars($app['description']) ?></p>
                    </div>
                    <a href="<?= htmlspecialchars($app['app_url']) ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-external-link-alt"></i> Visit
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Suggestion Box -->
        <div class="suggestion-box">
            <div class="suggestion-content">
                <i class="fas fa-lightbulb"></i>
                <div>
                    <h3>Know a Great Educational App?</h3>
                    <p>If you know of a free educational resource that should be on this list, let us know!</p>
                </div>
                <a href="contact.php" class="btn btn-outline">Suggest an App</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
