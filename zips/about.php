
<?php
/**
 * ZIPS Chau E-Library - About Page
 */
$pageTitle = 'About Us';
require_once 'includes/functions.php';
requireAuth();
?>
<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header about-header">
    <div class="container">
        <div class="page-header-content">
            <h1>About ZIPS Chau</h1>
            <p>Learn about the Zambia Institute of Purchasing and Supply - Chalimbana University Chapter</p>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-content">
                <div class="about-intro">
                    <span class="section-label">Our Story</span>
                    <h2>Established in July 2025</h2>
                    <p>The ZIPS Chalimbana University Chapter (ZIPS Chau) was established in July 2025 as a student chapter of the Zambia Institute of Purchasing and Supply. Our chapter serves students and professionals at Chalimbana University who are pursuing careers in procurement and supply chain management.</p>
                </div>

                <div class="about-mission">
                    <h3>Our Mission</h3>
                    <div class="mission-box">
                        <i class="fas fa-bullseye"></i>
                        <p><strong>Linking Procurement Theory to Practical Skills</strong></p>
                        <p>We are dedicated to bridging the gap between academic knowledge and real-world procurement practices, preparing our members for successful careers in the field.</p>
                    </div>
                </div>

                <div class="about-values">
                    <h3>Our Core Values</h3>
                    <div class="values-grid">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <h4>Excellence</h4>
                            <p>Striving for the highest standards in procurement education and practice.</p>
                        </div>
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <h4>Integrity</h4>
                            <p>Upholding ethical practices in all procurement activities.</p>
                        </div>
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4>Collaboration</h4>
                            <p>Working together to achieve common goals and share knowledge.</p>
                        </div>
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h4>Innovation</h4>
                            <p>Embracing new ideas and technologies in procurement management.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-sidebar">
                <div class="info-card">
                    <h3>Quick Facts</h3>
                    <ul class="facts-list">
                        <li>
                            <i class="fas fa-calendar-alt"></i>
                            <span><strong>Established:</strong> July 2025</span>
                        </li>
                        <li>
                            <i class="fas fa-university"></i>
                            <span><strong>Institution:</strong> Chalimbana University</span>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span><strong>Location:</strong> Chongwe, Zambia</span>
                        </li>
                        <li>
                            <i class="fas fa-building"></i>
                            <span><strong>Parent Body:</strong> ZIPS</span>
                        </li>
                    </ul>
                </div>

                <div class="info-card">
                    <h3>About ZIPS</h3>
                    <p>The Zambia Institute of Purchasing and Supply (ZIPS) is the professional body for procurement and supply chain professionals in Zambia. It provides professional certification, training, and advocacy for the procurement profession.</p>
                    <a href="https://zips.org.zm" target="_blank" class="btn btn-outline btn-block">
                        <i class="fas fa-external-link-alt"></i> Visit ZIPS Website
                    </a>
                </div>

                <div class="info-card cta-card">
                    <h3>Join Our Chapter</h3>
                    <p>Interested in becoming a member? Contact us to learn about membership benefits and requirements.</p>
                    <a href="contact.php" class="btn btn-primary btn-block">
                        <i class="fas fa-user-plus"></i> Get In Touch
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Objectives Section -->
<section class="objectives-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">What We Do</span>
            <h2>Chapter Objectives</h2>
            <p>Our key focus areas and initiatives</p>
        </div>

        <div class="objectives-grid">
            <div class="objective-card">
                <div class="objective-number">01</div>
                <h3>Professional Development</h3>
                <p>Organize workshops, seminars, and training sessions to enhance members' professional skills in procurement and supply chain management.</p>
            </div>
            <div class="objective-card">
                <div class="objective-number">02</div>
                <h3>Resource Sharing</h3>
                <p>Provide access to study materials, research papers, and educational resources through our digital library platform.</p>
            </div>
            <div class="objective-card">
                <div class="objective-number">03</div>
                <h3>Networking</h3>
                <p>Create opportunities for students and professionals to connect, share experiences, and build lasting relationships in the industry.</p>
            </div>
            <div class="objective-card">
                <div class="objective-number">04</div>
                <h3>Career Guidance</h3>
                <p>Support members in their career development through mentorship programs, internship opportunities, and job placement assistance.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
