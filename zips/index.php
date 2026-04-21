
<?php
/**
 * ZIPS Chau E-Library - Homepage
 */
$pageTitle = 'Home';
require_once 'includes/functions.php';
requireAuth();

$stats = getStats();
$recentResources = getResources(null, 6);
?>
<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <span class="hero-badge">Chalimbana University Chapter</span>
            <h1>Welcome to ZIPS Chau E-Library</h1>
            <p>Your digital gateway to procurement and supply chain management resources. Access study materials, video lectures, articles, and more.</p>
            <div class="hero-buttons">
                <a href="library.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-book-open"></i> Browse Library
                </a>
                <a href="about.php" class="btn btn-outline btn-lg">
                    <i class="fas fa-info-circle"></i> Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number"><?= number_format($stats['total_modules']) ?></span>
                    <span class="stat-label">Study Modules</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #27ae60, #219a52);">
                    <i class="fas fa-images"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number"><?= number_format($stats['total_pictures']) ?></span>
                    <span class="stat-label">Photos</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <i class="fas fa-video"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number"><?= number_format($stats['total_videos']) ?></span>
                    <span class="stat-label">Videos</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number"><?= number_format($stats['total_articles']) ?></span>
                    <span class="stat-label">Articles</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">What We Offer</span>
            <h2>Comprehensive Learning Resources</h2>
            <p>Everything you need to excel in procurement and supply chain management</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Study Modules</h3>
                <p>Access comprehensive study materials covering all aspects of procurement and supply chain management.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-play-circle"></i>
                </div>
                <h3>Video Lectures</h3>
                <p>Watch expert-led video lectures and tutorials to enhance your understanding of complex topics.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h3>Research Articles</h3>
                <p>Read the latest research and articles on procurement practices in Zambia and beyond.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-share-alt"></i>
                </div>
                <h3>Easy Sharing</h3>
                <p>Share resources with colleagues and classmates using unique shareable links.</p>
            </div>
        </div>
    </div>
</section>

<!-- Recent Resources Section -->
<section class="resources-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Latest Additions</span>
            <h2>Recent Resources</h2>
            <p>Check out the newest materials added to our library</p>
        </div>

        <div class="resources-grid">
            <?php foreach ($recentResources as $resource): ?>
            <div class="resource-card">
                <div class="resource-type-badge <?= getTypeBadgeClass($resource['file_type']) ?>">
                    <?= ucfirst($resource['file_type']) ?>
                </div>
                <div class="resource-icon">
                    <i class="fas <?= getFileIcon($resource['file_name']) ?>"></i>
                </div>
                <div class="resource-content">
                    <h3><?= htmlspecialchars($resource['title']) ?></h3>
                    <p><?= htmlspecialchars(substr($resource['description'] ?? '', 0, 80)) ?>...</p>
                    <div class="resource-meta">
                        <span><i class="fas fa-eye"></i> <?= number_format($resource['views']) ?> views</span>
                        <span><i class="fas fa-clock"></i> <?= timeAgo($resource['upload_date']) ?></span>
                    </div>
                </div>
                <a href="view.php?id=<?= $resource['id'] ?>" class="resource-link">
                    View Resource <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="section-footer">
            <a href="library.php" class="btn btn-primary">
                View All Resources <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Join the ZIPS Community</h2>
            <p>Connect with fellow procurement professionals and students. Stay updated with the latest resources and events.</p>
            <div class="cta-buttons">
                <a href="contact.php" class="btn btn-white btn-lg">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
                <a href="graduates.php" class="btn btn-outline-white btn-lg">
                    <i class="fas fa-user-graduate"></i> Meet Our Graduates
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
