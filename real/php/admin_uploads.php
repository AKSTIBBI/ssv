<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';

require_admin_login();

$flash_message = '';
$flash_type = '';
$errors = [];

// Upload directory
$upload_dir = dirname(__FILE__) . '/../uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Allowed file types and max size
$allowed_types = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'zip'];
$max_size = 5 * 1024 * 1024; // 5MB
$max_files_per_upload = 5;

// Handle file uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        $flash_message = "Security token invalid. Please try again.";
        $flash_type = 'error';
    } elseif (!empty($_FILES['files']['name'][0])) {
        $uploaded_count = 0;
        $file_count = count($_FILES['files']['name']);

        if ($file_count > $max_files_per_upload) {
            $errors[] = "Maximum {$max_files_per_upload} files can be uploaded at once.";
        }

        for ($i = 0; $i < $file_count; $i++) {
            if ($_FILES['files']['error'][$i] !== UPLOAD_ERR_OK) {
                $errors[] = "Error uploading {$_FILES['files']['name'][$i]}: " . $_FILES['files']['error'][$i];
                continue;
            }

            $filename = $_FILES['files']['name'][$i];
            $filesize = $_FILES['files']['size'][$i];
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            // Validate file
            if ($filesize > $max_size) {
                $errors[] = "{$filename} exceeds maximum size of 5MB.";
                continue;
            }

            if (!in_array($file_ext, $allowed_types)) {
                $errors[] = "{$filename} has unsupported file type (.{$file_ext}).";
                continue;
            }

            // Create safe filename
            $new_filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $upload_path)) {
                chmod($upload_path, 0644);
                $uploaded_count++;
                log_message("File uploaded: {$new_filename}");
            } else {
                $errors[] = "Failed to save {$filename}.";
            }
        }

        if ($uploaded_count > 0) {
            $flash_message = "{$uploaded_count} file(s) uploaded successfully!";
            $flash_type = 'success';
            header("Refresh: 2; url=" . $_SERVER['PHP_SELF']);
            exit;
        }
    } else {
        $errors[] = "Please select files to upload.";
    }
}

// Get list of uploaded files
$uploads = [];
if (is_dir($upload_dir)) {
    $files = array_diff(scandir($upload_dir), ['.', '..']);
    foreach ($files as $file) {
        $filepath = $upload_dir . $file;
        if (is_file($filepath)) {
            $uploads[] = [
                'name' => $file,
                'path' => 'uploads/' . $file,
                'size' => filesize($filepath),
                'type' => strtoupper(pathinfo($file, PATHINFO_EXTENSION)),
                'date' => date('Y-m-d H:i:s', filemtime($filepath))
            ];
        }
    }
}

// Sort by date (newest first)
usort($uploads, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Handle file deletion
if (isset($_GET['action']) && sanitize_input($_GET['action']) === 'delete' && isset($_GET['file'])) {
    $file_to_delete = basename(sanitize_input($_GET['file']));
    $file_path = $upload_dir . $file_to_delete;

    if (file_exists($file_path)) {
        if (unlink($file_path)) {
            log_message("File deleted: {$file_to_delete}");
            header("Location: admin_uploads.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Manager - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --color-primary: #244855;
            --color-primary-700: #1e3f49;
            --color-surface: #ffffff;
            --color-bg: #f3f6fa;
            --color-text: #1f3448;
            --color-muted: #5f7386;
            --color-border: #d8e1ea;
            --color-success-bg: #e9f8ef;
            --color-success-text: #0b6b3c;
            --color-success-border: #9ad7b4;
            --color-danger-bg: #fdecec;
            --color-danger-text: #8f1f1f;
            --color-danger-border: #f4b6b6;
            --radius-sm: 8px;
            --radius-md: 14px;
            --shadow-sm: 0 4px 12px rgba(16, 32, 48, 0.08);
            --shadow-md: 0 12px 30px rgba(16, 32, 48, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            min-height: 100vh;
            padding: 24px 16px;
        }

        .container {
            max-width: 1240px;
            margin: 0 auto;
        }

        .header { background:linear-gradient(135deg,var(--color-primary) 0%,var(--color-primary-700) 100%); color:#fff; border-radius:var(--radius-md); box-shadow:var(--shadow-sm); padding:22px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; }
        .header h1 { font-size:clamp(24px,3vw,30px); }
        .admin-nav { display:flex; flex-wrap:wrap; gap:8px; padding:8px; margin-bottom:16px; background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); }
        .admin-nav-link { display:inline-flex; align-items:center; gap:6px; padding:9px 12px; border-radius:var(--radius-sm); text-decoration:none; color:var(--color-primary); font-size:14px; font-weight:500; transition:.2s; }
        .admin-nav-link:hover { background:#eff4f8; }
        .admin-nav-link.active { background:var(--color-primary); color:#fff; }
        .btn-logout { padding:10px 14px; background:#c62828; color:#fff; border:1px solid #c62828; border-radius:var(--radius-sm); cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-weight:600; transition:all .2s ease; }
        .btn-logout:hover { background:#a91f1f; border-color:#a91f1f; }

        .content {
            margin-top: 20px;
            background: var(--color-surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border);
            box-shadow: var(--shadow-sm);
            padding: 28px;
        }

        .flash-message {
            padding: 12px 14px;
            border-radius: var(--radius-sm);
            margin-bottom: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.25s ease;
        }

        .flash-message.success {
            background: var(--color-success-bg);
            color: var(--color-success-text);
            border: 1px solid var(--color-success-border);
        }

        .flash-message.error {
            background: var(--color-danger-bg);
            color: var(--color-danger-text);
            border: 1px solid var(--color-danger-border);
        }

        .flash-close-btn {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
        }

        .error-list {
            margin-bottom: 18px;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-danger-border);
            background: #fff6f6;
            color: var(--color-danger-text);
        }

        .error-list ul {
            margin-left: 18px;
            line-height: 1.6;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }

        .stat-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbfd 100%);
            padding: 16px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-border);
            box-shadow: var(--shadow-sm);
        }

        .stat-number {
            font-size: clamp(22px, 3vw, 30px);
            font-weight: 700;
            color: var(--color-primary);
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .stat-label {
            color: var(--color-muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.35px;
        }

        .btn {
            padding: 10px 14px;
            background: var(--color-primary);
            color: var(--color-surface);
            border: 1px solid var(--color-primary);
            border-radius: var(--radius-sm);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background: var(--color-primary-700);
            border-color: var(--color-primary-700);
        }

        .upload-zone {
            border: 2px dashed #b7c9d8;
            border-radius: var(--radius-md);
            background: linear-gradient(180deg, #ffffff 0%, #f7fbfe 100%);
            padding: 26px 20px;
            text-align: center;
            transition: border-color 0.2s ease, background-color 0.2s ease, transform 0.2s ease;
        }

        .upload-zone.dragover {
            border-color: var(--color-primary);
            background: #eef7fc;
            transform: translateY(-1px);
        }

        .upload-zone .fa-cloud-upload-alt {
            font-size: 40px;
            color: var(--color-primary);
            margin-bottom: 12px;
        }

        .upload-zone p {
            margin-bottom: 8px;
            color: var(--color-text);
        }

        .upload-zone p:last-of-type {
            color: var(--color-muted);
            font-size: 13px;
        }

        #fileInput {
            display: none;
        }

        .upload-help {
            display: block;
            margin-top: 14px;
            color: var(--color-muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .selected-files-info {
            margin-top: 10px;
            font-size: 13px;
            color: var(--color-primary);
            font-weight: 600;
            min-height: 20px;
        }

        .upload-browse-btn {
            margin-top: 10px;
        }

        .upload-submit-btn {
            margin-top: 14px;
            display: none;
        }

        .file-list-title {
            margin: 30px 0 14px;
            font-size: 22px;
            color: var(--color-primary);
        }

        .empty-state {
            border: 1px dashed var(--color-border);
            border-radius: var(--radius-md);
            background: #f9fbfd;
            text-align: center;
            padding: 40px 20px;
            color: var(--color-muted);
        }

        .empty-state .fa-inbox {
            font-size: 38px;
            color: #8ea3b5;
            margin-bottom: 10px;
        }

        .file-list {
            display: grid;
            gap: 12px;
        }

        .file-card {
            display: grid;
            grid-template-columns: 56px minmax(0, 1fr) 220px 190px;
            align-items: center;
            gap: 14px;
            background: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            padding: 12px;
        }

        .file-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 20px;
            background: #eef4f8;
        }

        .file-icon.pdf { color: #c62828; background: #ffebee; }
        .file-icon.doc { color: #1565c0; background: #e8f1ff; }
        .file-icon.image { color: #2e7d32; background: #eaf8ec; }
        .file-icon.archive { color: #6d4c41; background: #f4eee9; }

        .file-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--color-text);
            overflow-wrap: anywhere;
        }

        .file-info {
            font-size: 12px;
            color: var(--color-muted);
            line-height: 1.6;
        }

        .file-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .btn-download,
        .btn-delete {
            padding: 8px 10px;
            border-radius: 7px;
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-download {
            background: #1f7a44;
        }

        .btn-download:hover {
            background: #166437;
        }

        .btn-delete {
            background: #c62828;
        }

        .btn-delete:hover {
            background: #a91f1f;
        }

        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 900px) {
            .content {
                padding: 20px;
            }

            .file-card {
                grid-template-columns: 48px minmax(0, 1fr);
            }

            .file-actions {
                justify-content: flex-start;
                grid-column: 1 / -1;
                margin-top: 4px;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 16px 10px;
            }

            .header {
                padding: 18px;
            }

            .content {
                padding: 16px;
            }

            .admin-nav {
                gap: 6px;
            }

            .admin-nav-link {
                font-size: 12px;
                padding: 8px 10px;
            }

            .file-list-title {
                font-size: 19px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📤 Upload Manager</h1>
            <a href="admin_logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <?php include 'admin_nav.php'; ?>

        <div class="content">
            <?php if ($flash_message): ?>
                <div class="flash-message <?php echo $flash_type; ?>">
                    <span>
                        <?php echo $flash_type === 'success' 
                            ? '<i class="fas fa-check-circle"></i>' 
                            : '<i class="fas fa-exclamation-circle"></i>'; ?>
                        <?php echo safe_output($flash_message); ?>
                    </span>
                    <button type="button" class="flash-close-btn" onclick="this.parentElement.style.display='none';">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo safe_output($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Statistics -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($uploads); ?></div>
                    <div class="stat-label">Total Files</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo round(array_sum(array_map(fn($u) => $u['size'], $uploads)) / (1024 * 1024), 2); ?> MB</div>
                    <div class="stat-label">Total Size</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">5 MB</div>
                    <div class="stat-label">Max Per File</div>
                </div>
            </div>

            <!-- Upload Zone -->
            <form method="POST" enctype="multipart/form-data" id="uploadForm">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                
                <div class="upload-zone" id="uploadZone">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p><strong>Drag and drop files here</strong></p>
                    <p>or</p>
                    <button type="button" class="btn upload-browse-btn" onclick="document.getElementById('fileInput').click();">
                        <i class="fas fa-folder-open"></i> Browse Files
                    </button>
                    <input type="file" id="fileInput" name="files[]" multiple>
                    <small class="upload-help">
                        Allowed: PDF, DOC, XLS, JPG, PNG, GIF, ZIP (Max 5MB per file, 5 files at once)
                    </small>
                    <p class="selected-files-info" id="selectedFilesInfo"></p>
                </div>

                <button type="submit" class="btn upload-submit-btn" id="submitBtn">
                    <i class="fas fa-upload"></i> Upload Files
                </button>
            </form>

            <!-- Files List -->
            <h2 class="file-list-title">📁 Uploaded Files</h2>
            
            <?php if (empty($uploads)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No files uploaded yet. Upload your first file using the upload zone above!</p>
                </div>
            <?php else: ?>
                <div class="file-list">
                    <?php foreach ($uploads as $file): 
                        // Determine file type icon and color
                        $ext = $file['type'];
                        $icon = 'fas fa-file';
                        $color_class = 'archive';
                        
                        if (strpos('PDF', $ext) === 0) { $icon = 'fas fa-file-pdf'; $color_class = 'pdf'; }
                        elseif (in_array($ext, ['DOC', 'DOCX'])) { $icon = 'fas fa-file-word'; $color_class = 'doc'; }
                        elseif (in_array($ext, ['XLS', 'XLSX'])) { $icon = 'fas fa-file-excel'; $color_class = 'doc'; }
                        elseif (in_array($ext, ['JPG', 'JPEG', 'PNG', 'GIF'])) { $icon = 'fas fa-file-image'; $color_class = 'image'; }
                        elseif ($ext === 'ZIP') { $icon = 'fas fa-file-archive'; $color_class = 'archive'; }
                    ?>
                        <div class="file-card">
                            <div class="file-icon <?php echo $color_class; ?>">
                                <i class="<?php echo $icon; ?>"></i>
                            </div>
                            <div class="file-name"><?php echo safe_output($file['name']); ?></div>
                            <div class="file-info">
                                <?php echo format_file_size($file['size']); ?><br>
                                <small><?php echo $file['date']; ?></small>
                            </div>
                            <div class="file-actions">
                                <a href="<?php echo safe_output($file['path']); ?>" download class="btn-download">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <a href="?action=delete&file=<?php echo urlencode($file['name']); ?>" class="btn-delete" onclick="return confirm('Delete this file?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Drag and drop functionality
        const uploadZone = document.getElementById('uploadZone');
        const fileInput = document.getElementById('fileInput');
        const uploadForm = document.getElementById('uploadForm');
        const submitBtn = document.getElementById('submitBtn');
        const selectedFilesInfo = document.getElementById('selectedFilesInfo');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadZone.addEventListener(eventName, () => {
                uploadZone.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, () => {
                uploadZone.classList.remove('dragover');
            });
        });

        uploadZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFiles();
        });

        fileInput.addEventListener('change', handleFiles);

        function handleFiles() {
            if (fileInput.files.length > 0) {
                const count = fileInput.files.length;
                selectedFilesInfo.textContent = `${count} file${count > 1 ? 's' : ''} selected`;
                submitBtn.style.display = 'inline-flex';
            } else {
                selectedFilesInfo.textContent = '';
                submitBtn.style.display = 'none';
            }
        }

        // Click on upload zone to open file picker
        uploadZone.addEventListener('click', () => {
            fileInput.click();
        });

        uploadForm.addEventListener('submit', () => {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        });
    </script>
</body>
</html>

