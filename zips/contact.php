
<?php
/**
 * ZIPS Chau E-Library - Contact Page
 */
$pageTitle = 'Contact Us';
require_once 'includes/functions.php';
requireAuth();

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    // Validate
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Save to database
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $subject, $message]);
                $success = 'Thank you for your message. We will get back to you soon!';
                // Clear form
                $name = $email = $subject = $message = '';
            } catch (PDOException $e) {
                $error = 'An error occurred. Please try again later.';
            }
        } else {
            // Demo mode - just show success
            $success = 'Thank you for your message. We will get back to you soon!';
            $name = $email = $subject = $message = '';
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header contact-header">
    <div class="container">
        <div class="page-header-content">
            <h1>Contact Us</h1>
            <p>Get in touch with the ZIPS Chau Chapter. We would love to hear from you!</p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form-wrapper">
                <h2>Send Us a Message</h2>
                <p>Have questions or suggestions? Fill out the form below and we will respond as soon as possible.</p>

                <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>

                <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="" class="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name <span class="required">*</span></label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="<?= htmlspecialchars($name ?? '') ?>"
                                   placeholder="Enter your full name"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="<?= htmlspecialchars($email ?? '') ?>"
                                   placeholder="Enter your email"
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text"
                               id="subject"
                               name="subject"
                               value="<?= htmlspecialchars($subject ?? '') ?>"
                               placeholder="What is this regarding?">
                    </div>

                    <div class="form-group">
                        <label for="message">Message <span class="required">*</span></label>
                        <textarea id="message"
                                  name="message"
                                  rows="6"
                                  placeholder="Type your message here..."
                                  required><?= htmlspecialchars($message ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="contact-info">
                <div class="info-card">
                    <h3>Contact Information</h3>
                    <ul class="contact-list">
                        <li>
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Address</h4>
                                <p>Chalimbana University<br>Chongwe District<br>Zambia</p>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Email</h4>
                                <p><a href="mailto:chau@zips.com">chau@zips.com</a></p>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Phone</h4>
                                <p>+260 772842016</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="info-card social-card">
                    <h3>Connect With Us</h3>
                    <p>Follow us on social media for updates and news.</p>
                    <div class="social-links">
                        <a href="https://facebook.com" target="_blank" class="social-link facebook">
                            <i class="fab fa-facebook-f"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="https://twitter.com" target="_blank" class="social-link twitter">
                            <i class="fab fa-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        <a href="https://linkedin.com" target="_blank" class="social-link linkedin">
                            <i class="fab fa-linkedin-in"></i>
                            <span>LinkedIn</span>
                        </a>
                        <a href="https://youtube.com" target="_blank" class="social-link youtube">
                            <i class="fab fa-youtube"></i>
                            <span>YouTube</span>
                        </a>
                    </div>
                </div>

                <div class="info-card hours-card">
                    <h3>Office Hours</h3>
                    <ul class="hours-list">
                        <li>
                            <span>Monday - Friday</span>
                            <span>8:00 AM - 5:00 PM</span>
                        </li>
                        <li>
                            <span>Saturday</span>
                            <span>9:00 AM - 1:00 PM</span>
                        </li>
                        <li>
                            <span>Sunday</span>
                            <span>Closed</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
