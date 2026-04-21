
<?php
/**
 * ZIPS Chau E-Library - Admin Upload Resource
 */
$pageTitle = 'Upload Resource';
require_once '../includes/functions.php';
requireAdmin();

$message = '';
$error = '';

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $file_type = sanitize($_POST['file_type'] ?? '');

    // Validate
    if (empty($title) || empty($file_type)) {
        $error = 'Please fill in all required fields.';
    } elseif (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please select a file to upload.';
    } else {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmp = $file['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate file type
        $allowedTypes = [
            'module' => ALLOWED_MODULES,
            'picture' => ALLOWED_PICTURES,
            'video' => ALLOWED_VIDEOS,
            'article' => ALLOWED_ARTICLES
        ];

        if (!isset($allowedTypes[$file_type]) || !in_array($fileExt, $allowedTypes[$file_type])) {
            $error = 'Invalid file type for the selected category.';
        } elseif ($fileSize > MAX_UPLOAD_SIZE) {
            $error = 'File size exceeds maximum allowed (50MB).';
        } else {
            // Generate unique filename
            $newFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $fileName);

            // Determine upload directory
            $uploadDirs = [
                'module' => 'modules',
                'picture' => 'pics',
                'video' => 'videos',
                'article' => 'articles'
            ];
            $uploadDir = UPLOAD_PATH . $uploadDirs[$file_type] . '/';
            $filePath = $uploadDir . $newFileName;

            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Move file
            if (move_uploaded_file($fileTmp, $filePath)) {
                // Generate share token
                $shareToken = generateShareToken();

                // Save to database
                $pdo = getDBConnection();
                if ($pdo) {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO resources (title, description, file_name, file_path, file_type, file_size, share_token) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$title, $description, $newFileName, $filePath, $file_type, $fileSize, $shareToken]);
                        $message = 'Resource uploaded successfully!';

                        // Clear form
                        $title = $description = '';
                    } catch (PDOException $e) {
                        $error = 'Database error: ' . $e->getMessage();
                    }
                } else {
                    $message = 'Resource uploaded successfully! (Demo mode - not saved to database)';
                }
            } else {
                $error = 'Failed to upload file. Please try again.';
            }
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

<div class="admin-card">
    <div class="card-header">
        <h2>Upload New Resource</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data" class="admin-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="title">Resource Title <span class="required">*</span></label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="<?= htmlspecialchars($title ?? '') ?>"
                           placeholder="Enter resource title"
                           required>
                </div>

                <div class="form-group">
                    <label for="file_type">Resource Type <span class="required">*</span></label>
                    <select id="file_type" name="file_type" required>
                        <option value="">Select type...</option>
                        <option value="module">Module (PDF, DOC, PPT, XLS)</option>
                        <option value="picture">Picture (JPG, PNG, GIF, WEBP)</option>
                        <option value="video">Video (MP4, AVI, MOV, WEBM)</option>
                        <option value="article">Article (PDF, DOC, TXT)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description"
                          name="description"
                          rows="4"
                          placeholder="Enter a description for this resource..."><?= htmlspecialchars($description ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="file">Select File <span class="required">*</span></label>
                <div class="file-upload-area" id="fileUploadArea">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Drag and drop file here or click to browse</p>
                    <span class="file-info">Maximum file size: 1GB</span>
                    <input type="file" id="file" name="file" required>
                </div>
                <div class="selected-file" id="selectedFile" style="display: none;">
                    <i class="fas fa-file"></i>
                    <span id="fileName"></span>
                    <button type="button" onclick="clearFile()" class="btn btn-sm btn-ghost">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-upload"></i> Upload Resource
                </button>
                <a href="resources.php" class="btn btn-outline btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Upload Guidelines -->
<div class="admin-card">
    <div class="card-header">
        <h2>Upload Guidelines</h2>
    </div>
    <div class="card-body">
        <div class="guidelines-grid">
            <div class="guideline-item">
                <h4><i class="fas fa-book"></i> Modules</h4>
                <p>PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX</p>
            </div>
            <div class="guideline-item">
                <h4><i class="fas fa-images"></i> Pictures</h4>
                <p>JPG, JPEG, PNG, GIF, WEBP</p>
            </div>
            <div class="guideline-item">
                <h4><i class="fas fa-video"></i> Videos</h4>
                <p>MP4, AVI, MOV, WEBM, MKV</p>
            </div>
            <div class="guideline-item">
                <h4><i class="fas fa-file-alt"></i> Articles</h4>
                <p>PDF, DOC, DOCX, TXT</p>
            </div>
        </div>
    </div>
</div>

<script>
// File upload handling
const fileInput = document.getElementById('file');
const fileUploadArea = document.getElementById('fileUploadArea');
const selectedFile = document.getElementById('selectedFile');
const fileNameSpan = document.getElementById('fileName');

fileInput.addEventListener('change', function() {
    if (this.files.length > 0) {
        fileNameSpan.textContent = this.files[0].name;
        fileUploadArea.style.display = 'none';
        selectedFile.style.display = 'flex';
    }
});

function clearFile() {
    fileInput.value = '';
    fileUploadArea.style.display = 'block';
    selectedFile.style.display = 'none';
}

// Drag and drop
fileUploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

fileUploadArea.addEventListener('dragleave', function() {
    this.classList.remove('dragover');
});

fileUploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    fileInput.files = e.dataTransfer.files;
    fileInput.dispatchEvent(new Event('change'));
});
</script>

<?php include 'includes/admin-footer.php'; ?>
