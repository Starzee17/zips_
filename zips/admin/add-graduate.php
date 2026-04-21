
<?php
/**
 * ZIPS Chau E-Library - Admin Add Graduate
 */
$pageTitle = 'Add Graduate';
require_once '../includes/functions.php';
requireAdmin();

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name'] ?? '');
    $student_id = sanitize($_POST['student_id'] ?? '');
    $program = sanitize($_POST['program'] ?? '');
    $graduation_year = (int)($_POST['graduation_year'] ?? date('Y'));
    $graduation_date = sanitize($_POST['graduation_date'] ?? '');
    $honors = sanitize($_POST['honors'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    // Validate
    if (empty($full_name)) {
        $error = 'Please enter the graduate\'s full name.';
    } else {
        // Handle photo upload
        $photoName = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photo = $_FILES['photo'];
            $photoExt = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));

            if (in_array($photoExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $photoName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $photo['name']);
                $uploadDir = UPLOAD_PATH . 'graduates/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                move_uploaded_file($photo['tmp_name'], $uploadDir . $photoName);
            }
        }

        // Save to database
        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO graduates (full_name, student_id, program, graduation_year, graduation_date, honors, photo, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$full_name, $student_id, $program, $graduation_year, $graduation_date ?: null, $honors, $photoName, $is_published]);
                $message = 'Graduate added successfully!';

                // Clear form
                $full_name = $student_id = $program = $honors = '';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        } else {
            $message = 'Graduate added successfully! (Demo mode - not saved to database)';
        }
    }
}
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
<link rel="stylesheet" href="assets/css/dashboard.css">
<div class="admin-card">
    <div class="card-header">
        <h2>Add New Graduate</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data" class="admin-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">Full Name <span class="required">*</span></label>
                    <input type="text"
                           id="full_name"
                           name="full_name"
                           value="<?= htmlspecialchars($full_name ?? '') ?>"
                           placeholder="Enter full name"
                           required>
                </div>

                <div class="form-group">
                    <label for="student_id">Student ID</label>
                    <input type="text"
                           id="student_id"
                           name="student_id"
                           value="<?= htmlspecialchars($student_id ?? '') ?>"
                           placeholder="e.g., ZIPS2024001">
                </div>
            </div>

            <div class="form-group">
                <label for="program">Program/Certification</label>
                <input type="text"
                       id="program"
                       name="program"
                       value="<?= htmlspecialchars($program ?? '') ?>"
                       placeholder="e.g., Diploma in Procurement and Supply">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="graduation_year">Graduation Year</label>
                    <select id="graduation_year" name="graduation_year">
                        <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                        <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="graduation_date">Graduation Date</label>
                    <input type="date"
                           id="graduation_date"
                           name="graduation_date">
                </div>

                <div class="form-group">
                    <label for="honors">Honors/Grade</label>
                    <select id="honors" name="honors">
                        <option value="">None</option>
                        <option value="Distinction">Distinction</option>
                        <option value="Merit">Merit</option>
                        <option value="Credit">Credit</option>
                        <option value="Pass">Pass</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="photo">Photo</label>
                <div class="file-upload-area small" id="photoUploadArea">
                    <i class="fas fa-user-circle"></i>
                    <p>Click to upload photo</p>
                    <span class="file-info">JPG, PNG, GIF, WEBP (max 5MB)</span>
                    <input type="file" id="photo" name="photo" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_published" value="1">
                    <span>Publish immediately</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus"></i> Add Graduate
                </button>
                <a href="graduates.php" class="btn btn-outline btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
