<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';
require_once 'financials_repository.php';

require_admin_login();

$action = $_POST['action'] ?? '';
$flash_message = '';
$flash_type = '';
$errors = [];

// Load financials data
$financials = financials_get_all();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        $flash_message = "Security token invalid. Please try again.";
        $flash_type = 'error';
    } elseif ($action === 'add') {
        $doc_data = [
            'id' => uniqid('doc_'),
            'title' => safe_trim($_POST['title'] ?? ''),
            'category' => safe_trim($_POST['category'] ?? ''),
            'description' => safe_trim($_POST['description'] ?? ''),
            'document_url' => safe_trim($_POST['document_url'] ?? ''),
            'date_added' => get_current_date(),
            'date_published' => safe_trim($_POST['date_published'] ?? ''),
            'visibility' => $_POST['visibility'] ?? 'public',
            'status' => 'active'
        ];

        // Validation
        if (empty($doc_data['title'])) {
            $errors[] = 'Document title is required';
        }
        if (empty($doc_data['category'])) {
            $errors[] = 'Document category is required';
        }
        if (empty($doc_data['document_url'])) {
            $errors[] = 'Document URL/Path is required';
        }

        if (empty($errors)) {
            if (financials_add($doc_data)) {
                log_message("Financial document added: {$doc_data['title']}");
                $flash_message = "Document added successfully!";
                $flash_type = 'success';
                header("Refresh: 2; url=" . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $flash_message = "Failed to save document. Please try again.";
                $flash_type = 'error';
            }
        }
    } elseif ($action === 'edit') {
        $edit_id = safe_trim($_POST['edit_id'] ?? '');
        $doc_data = [
            'title' => safe_trim($_POST['title'] ?? ''),
            'category' => safe_trim($_POST['category'] ?? ''),
            'description' => safe_trim($_POST['description'] ?? ''),
            'date_published' => safe_trim($_POST['date_published'] ?? ''),
            'visibility' => $_POST['visibility'] ?? 'public'
        ];

        // Only include document_url if it was updated
        $new_document_url = safe_trim($_POST['document_url'] ?? '');
        if (!empty($new_document_url)) {
            $doc_data['document_url'] = $new_document_url;
        }

        // Validation
        if (empty($doc_data['title'])) {
            $errors[] = 'Document title is required';
        }
        if (empty($doc_data['category'])) {
            $errors[] = 'Document category is required';
        }

        if (empty($errors)) {
            if (financials_update($edit_id, $doc_data)) {
                log_message("Financial document updated: {$doc_data['title']}");
                $flash_message = "Document updated successfully!";
                $flash_type = 'success';
                header("Refresh: 2; url=" . $_SERVER['PHP_SELF']);
                exit;
            }
        }
    } elseif ($action === 'delete') {
        $delete_id = safe_trim($_POST['delete_id'] ?? '');
        if (financials_delete($delete_id)) {
            log_message("Financial document deleted: {$delete_id}");
            $flash_message = "Document deleted successfully!";
            $flash_type = 'success';
            header("Refresh: 2; url=" . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

$financials = financials_get_all();

// Get document for editing
$edit_doc = null;
$edit_id = $_GET['edit'] ?? '';
if ($edit_id) {
    foreach ($financials as $doc) {
        if ($doc['id'] === $edit_id) {
            $edit_doc = $doc;
            break;
        }
    }
}

$is_editing = $edit_doc !== null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Documents - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --color-primary:#244855; --color-primary-700:#1e3f49; --color-surface:#ffffff; --color-bg:#f3f6fa; --color-text:#1f3448; --color-muted:#5f7386; --color-border:#d8e1ea; --radius-sm:8px; --radius-md:14px; --shadow-sm:0 4px 12px rgba(16,32,48,.08); }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:"Poppins","Segoe UI",Tahoma,Geneva,Verdana,sans-serif; background:var(--color-bg); color:var(--color-text); min-height:100vh; padding:20px 14px; }
        .container { max-width:1320px; margin:0 auto; }
        .header { background:linear-gradient(135deg,var(--color-primary) 0%,var(--color-primary-700) 100%); color:#fff; border-radius:var(--radius-md); box-shadow:var(--shadow-sm); padding:22px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; }
        .header h1 { font-size:clamp(24px,3vw,30px); }
        .admin-nav { display:flex; flex-wrap:wrap; gap:8px; padding:8px; margin-bottom:16px; background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); }
        .admin-nav-link { display:inline-flex; align-items:center; gap:6px; padding:9px 12px; border-radius:var(--radius-sm); text-decoration:none; color:var(--color-primary); font-size:14px; font-weight:500; transition:.2s; }
        .admin-nav-link:hover { background:#eff4f8; }
        .admin-nav-link.active { background:var(--color-primary); color:#fff; }
        .content { background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); padding:24px; }
        .flash-message { padding:12px 14px; border-radius:var(--radius-sm); margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; gap:8px; animation:slideIn .25s ease; }
        .flash-message.success { background:#e9f8ef; color:#0b6b3c; border:1px solid #9ad7b4; }
        .flash-message.error { background:#fdecec; color:#8f1f1f; border:1px solid #f4b6b6; }
        .flash-close-btn { background:none; border:none; color:inherit; cursor:pointer; font-size:18px; line-height:1; }
        .error-list { background:#fff6f6; color:#8f1f1f; padding:12px 16px; border-radius:var(--radius-sm); margin-bottom:16px; border:1px solid #f4b6b6; }
        .error-list ul { margin-left:18px; line-height:1.6; }
        .form-section { border:1px solid var(--color-border); border-radius:var(--radius-md); background:#fbfdff; padding:18px; margin-bottom:22px; }
        .form-section h2 { color:var(--color-primary); margin-bottom:14px; font-size:22px; }
        .form-row { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; }
        .form-group { margin-bottom:14px; }
        .form-group label { display:block; margin-bottom:6px; color:var(--color-primary); font-weight:600; font-size:14px; }
        .form-group input,.form-group textarea,.form-group select { width:100%; padding:10px; border:1px solid var(--color-border); border-radius:var(--radius-sm); font-size:14px; font-family:inherit; color:var(--color-text); background:#fff; }
        .form-group textarea { min-height:100px; resize:vertical; }
        .form-group input:focus,.form-group textarea:focus,.form-group select:focus { outline:none; border-color:var(--color-primary); box-shadow:0 0 0 3px rgba(36,72,85,.12); }
        .field-hint { display:block; margin-top:5px; color:var(--color-muted); font-size:12px; }
        .field-hint.success { color:#1f7a44; }
        .btn-group { display:flex; gap:10px; margin-top:10px; flex-wrap:wrap; }
        .btn { padding:10px 14px; border:1px solid transparent; border-radius:var(--radius-sm); cursor:pointer; font-weight:600; font-size:14px; transition:all .2s ease; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
        .btn-primary { background:var(--color-primary); color:#fff; border-color:var(--color-primary); }
        .btn-primary:hover { background:var(--color-primary-700); border-color:var(--color-primary-700); }
        .btn-secondary { background:#6c7e90; color:#fff; border-color:#6c7e90; }
        .btn-secondary:hover { background:#5b6c7d; border-color:#5b6c7d; }
        h2 { color:var(--color-primary); margin:0 0 10px; }
        .table-responsive { margin-top:12px; border:1px solid var(--color-border); border-radius:var(--radius-md); overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        thead { background:#f6f9fc; border-bottom:1px solid var(--color-border); }
        th,td { padding:12px; text-align:left; border-bottom:1px solid var(--color-border); font-size:14px; }
        th { color:var(--color-primary); font-weight:600; white-space:nowrap; }
        tbody tr:hover { background:#f9fbfd; }
        .badge { padding:4px 10px; border-radius:999px; font-size:12px; font-weight:600; display:inline-block; }
        .badge-public { background:#e9f8ef; color:#0b6b3c; }
        .badge-private { background:#fff3cd; color:#856404; }
        .badge-success { background:#e7f3f8; color:#0f4f66; }
        .action-buttons { display:flex; flex-wrap:wrap; gap:8px; }
        .btn-view,.btn-edit,.btn-delete { padding:8px 10px; border-radius:7px; color:#fff; text-decoration:none; font-size:12px; font-weight:600; display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; }
        .btn-view { background:#1f7a44; } .btn-view:hover { background:#166437; }
        .btn-edit { background:#1f5b95; } .btn-edit:hover { background:#174a79; }
        .btn-delete { background:#c62828; } .btn-delete:hover { background:#a91f1f; }
        .btn-logout { padding:10px 14px; background:#c62828; color:#fff; border:1px solid #c62828; border-radius:var(--radius-sm); cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-weight:600; transition:all .2s ease; }
        .btn-logout:hover { background:#a91f1f; border-color:#a91f1f; }
        .inline-form { display:inline; }
        .empty-state { text-align:center; padding:38px 16px; color:var(--color-muted); }
        .empty-state i { font-size:46px; margin-bottom:14px; display:block; color:#90a4b5; }
        @keyframes slideIn { from { transform:translateY(-10px); opacity:0; } to { transform:translateY(0); opacity:1; } }
        @media (max-width:900px) { .form-row { grid-template-columns:1fr; } }
        @media (max-width:600px) { .content { padding:16px; } .admin-nav-link { font-size:12px; padding:8px 10px; } th,td { padding:10px; font-size:13px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📄 Financial Documents</h1>
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

            <!-- Document Form -->
            <div class="form-section">
                <h2><?php echo $is_editing ? '✏️ Edit Document' : '➕ Add Financial Document'; ?></h2>
                <form method="POST" id="documentForm" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="action" value="<?php echo $is_editing ? 'edit' : 'add'; ?>">
                    <input type="hidden" id="document_url_hidden" name="document_url" value="<?php echo $is_editing ? safe_output($edit_doc['document_url']) : ''; ?>">
                    <?php if ($is_editing): ?>
                        <input type="hidden" name="edit_id" value="<?php echo safe_output($edit_doc['id']); ?>">
                    <?php endif; ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">Document Title *</label>
                            <input type="text" id="title" name="title" required 
                                value="<?php echo $is_editing ? safe_output($edit_doc['title']) : ''; ?>"
                                placeholder="e.g., Annual Financial Report 2023-24">
                        </div>
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" required>
                                <option value="">-- Select Category --</option>
                                <option value="Annual Report" <?php echo ($is_editing && $edit_doc['category'] === 'Annual Report') ? 'selected' : ''; ?>>Annual Report</option>
                                <option value="Audit Report" <?php echo ($is_editing && $edit_doc['category'] === 'Audit Report') ? 'selected' : ''; ?>>Audit Report</option>
                                <option value="Tax Document" <?php echo ($is_editing && $edit_doc['category'] === 'Tax Document') ? 'selected' : ''; ?>>Tax Document</option>
                                <option value="Legal Document" <?php echo ($is_editing && $edit_doc['category'] === 'Legal Document') ? 'selected' : ''; ?>>Legal Document</option>
                                <option value="Compliance" <?php echo ($is_editing && $edit_doc['category'] === 'Compliance') ? 'selected' : ''; ?>>Compliance</option>
                                <option value="Other" <?php echo ($is_editing && $edit_doc['category'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" 
                            placeholder="Brief description of the document"><?php echo $is_editing ? safe_output($edit_doc['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="document_file"><?php echo $is_editing ? 'Upload New Document (Optional)' : 'Select Document *'; ?></label>
                            <input type="file" id="document_file" name="document" 
                                <?php echo $is_editing ? '' : 'required'; ?>
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif"
                                onchange="updateDocumentInfo()">
                            <small class="field-hint">
                                Allowed: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, PNG, GIF (Max 50MB)
                            </small>
                            <?php if ($is_editing && !empty($edit_doc['document_url'])): ?>
                                <small class="field-hint success">
                                    <i class="fas fa-file"></i> Current file: <?php echo pathinfo($edit_doc['document_url'], PATHINFO_BASENAME); ?>
                                </small>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="date_published">Published Date</label>
                            <input type="date" id="date_published" name="date_published"
                                value="<?php echo $is_editing ? safe_output($edit_doc['date_published']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="visibility">Visibility *</label>
                        <select id="visibility" name="visibility" required>
                            <option value="public" <?php echo (!$is_editing || $edit_doc['visibility'] === 'public') ? 'selected' : ''; ?>>Public (Visible to all)</option>
                            <option value="restricted" <?php echo ($is_editing && $edit_doc['visibility'] === 'restricted') ? 'selected' : ''; ?>>Restricted (Admin only)</option>
                        </select>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 
                            <?php echo $is_editing ? 'Update Document' : 'Add Document'; ?>
                        </button>
                        <?php if ($is_editing): ?>
                            <a href="admin_financials.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Documents List -->
            <h2>📋 Financial Documents</h2>
            <div class="table-responsive">
                <?php if (empty($financials)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No documents added yet. Upload your first document to get started!</p>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Published Date</th>
                                <th>Visibility</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($financials as $doc): ?>
                                <tr>
                                    <td><strong><?php echo safe_output($doc['title']); ?></strong></td>
                                    <td><?php echo safe_output($doc['category']); ?></td>
                                    <td><?php echo safe_output($doc['date_published'] ?? get_current_date()); ?></td>
                                    <td>
                                        <span class="badge <?php echo $doc['visibility'] === 'public' ? 'badge-public' : 'badge-private'; ?>">
                                            <?php echo ucfirst($doc['visibility']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            <?php echo ucfirst($doc['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?php echo str_replace('real/', '../', safe_output($doc['document_url'])); ?>" target="_blank" class="btn-view">
                                                <i class="fas fa-external-link-alt"></i> View
                                            </a>
                                            <a href="?edit=<?php echo urlencode($doc['id']); ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form method="POST" class="inline-form" onsubmit="return confirm('Delete this document?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="delete_id" value="<?php echo safe_output($doc['id']); ?>">
                                                <button type="submit" class="btn-delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    function updateDocumentInfo() {
        const fileInput = document.getElementById('document_file');
        const file = fileInput.files[0];
        if (file) {
            const fileName = file.name;
            console.log('Selected file:', fileName);
        }
    }

    document.getElementById('documentForm').addEventListener('submit', async function(e) {
        const documentFile = document.getElementById('document_file');
        const titleField = document.getElementById('title');
        const categoryField = document.getElementById('category');
        const action = document.querySelector('input[name="action"]').value;

        // For new documents, file is required
        if (action === 'add') {
            if (!documentFile.files.length) {
                alert('Please select a document file to upload.');
                e.preventDefault();
                return false;
            }

            // Upload file first, then submit form
            e.preventDefault();

            const formData = new FormData();
            formData.append('document', documentFile.files[0]);

            try {
                const response = await fetch('upload_document.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Set the document URL and submit the form
                    document.getElementById('document_url_hidden').value = result.filePath;
                    // Submit the original form
                    document.getElementById('documentForm').submit();
                } else {
                    alert('File upload failed: ' + result.message);
                }
            } catch (error) {
                alert('Error uploading file: ' + error.message);
            }
        } else if (action === 'edit') {
            // For editing, file upload is optional
            if (documentFile.files.length) {
                e.preventDefault();

                const formData = new FormData();
                formData.append('document', documentFile.files[0]);

                try {
                    const response = await fetch('upload_document.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Set the document URL and submit the form
                        document.getElementById('document_url_hidden').value = result.filePath;
                        // Submit the original form
                        document.getElementById('documentForm').submit();
                    } else {
                        alert('File upload failed: ' + result.message);
                    }
                } catch (error) {
                    alert('Error uploading file: ' + error.message);
                }
            }
        }
    });
    </script>
</body>
</html>

