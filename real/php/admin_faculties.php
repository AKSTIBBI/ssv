<?php
/**
 * Admin Faculty Management Page
 * Handles CRUD operations for faculty data
 */

require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';

// Check authentication
require_admin_login();

// Check session timeout
if (Auth::has_timed_out()) {
    redirect('admin_login.php?session=expired');
}

// Initialize variables
$faculties = array();
$current_page = 'list';
$edit_id = null;
$edit_data = null;
$form_errors = array();
$flash_message = '';
$flash_type = '';

// Load faculty data
$faculties = get_json_data(FACULTY_JSON);

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!Auth::verify_csrf($csrf_token)) {
        die(json_encode([
            'success' => false,
            'message' => 'Invalid security token. Please try again.'
        ]));
    }
    
    $action = safe_trim($_POST['action'] ?? '');
    
    // ===== ADD FACULTY =====
    if ($action === 'add') {
        $name = safe_trim($_POST['name'] ?? '');
        $title = safe_trim($_POST['title'] ?? '');
        $image = safe_trim($_POST['image'] ?? '');
        
        $faculty_data = [
            'name' => $name,
            'title' => $title,
            'image' => $image
        ];
        
        // Validate
        $form_errors = validate_faculty($faculty_data);
        
        if (empty($form_errors)) {
            // Add to array
            $faculties[] = $faculty_data;
            
            // Save to file
            if (save_json_file(FACULTY_JSON, $faculties)) {
                $flash_message = "Faculty added successfully!";
                $flash_type = 'success';
                log_message("Faculty added: $name", 'info');
                header("Refresh: 2; url=" . $_SERVER['PHP_SELF']);
            } else {
                $flash_message = "Error saving faculty data. Check file permissions.";
                $flash_type = 'error';
                log_message("Failed to save faculty data", 'error');
            }
        } else {
            $flash_type = 'error';
        }
    }
    // ===== UPDATE FACULTY =====
    elseif ($action === 'edit') {
        $edit_index = intval($_POST['edit_id'] ?? -1);
        $name = safe_trim($_POST['name'] ?? '');
        $title = safe_trim($_POST['title'] ?? '');
        $image = safe_trim($_POST['image'] ?? '');
        
        $faculty_data = [
            'name' => $name,
            'title' => $title,
            'image' => $image
        ];
        
        // Validate
        $form_errors = validate_faculty($faculty_data);
        
        if (empty($form_errors)) {
            if ($edit_index >= 0 && $edit_index < count($faculties)) {
                $old_name = $faculties[$edit_index]['name'];
                $faculties[$edit_index] = $faculty_data;
                
                if (save_json_file(FACULTY_JSON, $faculties)) {
                    $flash_message = "Faculty updated successfully!";
                    $flash_type = 'success';
                    log_message("Faculty updated: $old_name → $name", 'info');
                    header("Refresh: 2; url=" . $_SERVER['PHP_SELF']);
                } else {
                    $flash_message = "Error updating faculty data.";
                    $flash_type = 'error';
                    log_message("Failed to update faculty: $name", 'error');
                }
            } else {
                $flash_message = "Invalid faculty ID.";
                $flash_type = 'error';
            }
        } else {
            $flash_type = 'error';
        }
    }
    // ===== DELETE FACULTY =====
    elseif ($action === 'delete') {
        $delete_id = intval($_POST['delete_id'] ?? -1);
        
        if ($delete_id >= 0 && $delete_id < count($faculties)) {
            $deleted_name = $faculties[$delete_id]['name'];
            array_splice($faculties, $delete_id, 1);
            
            if (save_json_file(FACULTY_JSON, $faculties)) {
                $flash_message = "Faculty deleted successfully!";
                $flash_type = 'success';
                log_message("Faculty deleted: $deleted_name", 'info');
                header("Refresh: 1; url=" . $_SERVER['PHP_SELF']);
            } else {
                $flash_message = "Error deleting faculty.";
                $flash_type = 'error';
                log_message("Failed to delete faculty: $deleted_name", 'error');
            }
        } else {
            $flash_message = "Invalid faculty ID.";
            $flash_type = 'error';
        }
    }
}

// Check if showing edit form
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    if ($edit_id >= 0 && $edit_id < count($faculties)) {
        $current_page = 'form';
        $edit_data = $faculties[$edit_id];
    }
}

// Check if showing add form
if (isset($_GET['add'])) {
    $current_page = 'form';
    $edit_id = null;
}

// Reload faculties list after potential modifications
$faculties = get_json_data(FACULTY_JSON);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Management - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --color-primary:#244855; --color-primary-700:#1e3f49; --color-surface:#ffffff; --color-bg:#f3f6fa; --color-text:#1f3448; --color-muted:#5f7386; --color-border:#d8e1ea; --radius-sm:8px; --radius-md:14px; --shadow-sm:0 4px 12px rgba(16,32,48,.08); }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:"Poppins","Segoe UI",Tahoma,Geneva,Verdana,sans-serif; background:var(--color-bg); color:var(--color-text); min-height:100vh; padding:20px 14px; }
        .admin-container { max-width:1320px; margin:0 auto; }
        .admin-container h2 { background:linear-gradient(135deg,var(--color-primary) 0%,var(--color-primary-700) 100%); color:#fff; border-radius:var(--radius-md); box-shadow:var(--shadow-sm); padding:24px; margin-bottom:16px; text-align:left; font-size:clamp(24px,3vw,30px); }
        .admin-nav { display:flex; flex-wrap:wrap; gap:8px; padding:8px; margin-bottom:16px; background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); }
        .admin-nav-link { display:inline-flex; align-items:center; gap:6px; padding:9px 12px; border-radius:var(--radius-sm); text-decoration:none; color:var(--color-primary); font-size:14px; font-weight:500; transition:all .2s ease; }
        .admin-nav-link:hover { background:#eff4f8; color:var(--color-primary-700); }
        .admin-nav-link.active { background:var(--color-primary); color:#fff; }
        .admin-content { background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); padding:24px; }

        .alert { padding:12px 14px; border-radius:var(--radius-sm); margin-bottom:14px; display:flex; gap:10px; align-items:flex-start; }
        .alert-success { background:#e9f8ef; color:#0b6b3c; border:1px solid #9ad7b4; }
        .alert-error { background:#fff6f6; color:#8f1f1f; border:1px solid #f4b6b6; }
        .alert ul { margin:8px 0 0 18px; line-height:1.6; }

        .content-section { border:1px solid var(--color-border); border-radius:var(--radius-md); background:#fbfdff; overflow:hidden; }
        .section-header { display:flex; align-items:center; justify-content:space-between; gap:10px; padding:14px 16px; border-bottom:1px solid var(--color-border); background:#f6f9fc; }
        .section-header h3 { color:var(--color-primary); font-size:20px; margin:0; display:inline-flex; align-items:center; gap:8px; }
        .section-body { padding:16px; }

        .admin-form { max-width:900px; }
        .form-group { margin-bottom:14px; }
        .form-label { display:block; margin-bottom:6px; color:var(--color-primary); font-weight:600; font-size:14px; }
        .form-control { width:100%; padding:10px; border:1px solid var(--color-border); border-radius:var(--radius-sm); font-size:14px; font-family:inherit; color:var(--color-text); background:#fff; }
        .form-control:focus { outline:none; border-color:var(--color-primary); box-shadow:0 0 0 3px rgba(36,72,85,.12); }
        .form-text { display:block; margin-top:5px; color:var(--color-muted); font-size:12px; }

        .form-actions { display:flex; gap:10px; margin-top:8px; flex-wrap:wrap; }
        .btn-primary,.btn-secondary,.action-btn { padding:10px 14px; border-radius:var(--radius-sm); border:1px solid transparent; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-size:14px; font-weight:600; cursor:pointer; transition:all .2s ease; }
        .btn-primary { background:var(--color-primary); color:#fff; border-color:var(--color-primary); }
        .btn-primary:hover { background:var(--color-primary-700); border-color:var(--color-primary-700); }
        .btn-secondary { background:#6c7e90; color:#fff; border-color:#6c7e90; }
        .btn-secondary:hover { background:#5b6c7d; border-color:#5b6c7d; }

        .table-wrap { overflow-x:auto; border:1px solid var(--color-border); border-radius:var(--radius-md); }
        table.data-table { width:100%; border-collapse:collapse; }
        table.data-table thead { background:#f6f9fc; border-bottom:1px solid var(--color-border); }
        table.data-table th, table.data-table td { padding:12px; text-align:left; border-bottom:1px solid var(--color-border); font-size:14px; }
        table.data-table th { color:var(--color-primary); font-weight:600; white-space:nowrap; }
        table.data-table tbody tr:hover { background:#f9fbfd; }

        .preview-image { width:52px; height:52px; object-fit:cover; border-radius:8px; border:1px solid var(--color-border); }
        .no-image { color:var(--color-muted); font-size:13px; }
        .action-cell { display:flex; gap:8px; }
        .action-btn { padding:8px 10px; font-size:12px; color:#fff; }
        .edit-btn { background:#1f5b95; border-color:#1f5b95; }
        .edit-btn:hover { background:#174a79; border-color:#174a79; }
        .delete-btn { background:#c62828; border-color:#c62828; }
        .delete-btn:hover { background:#a91f1f; border-color:#a91f1f; }
        .inline-form { display:inline; }

        .no-data-message { text-align:center; color:var(--color-muted); padding:24px 10px; }
        .no-data-icon { font-size:48px; color:#c0ccd7; display:block; margin-bottom:14px; }
        .no-data-link { color:var(--color-primary); font-weight:600; text-decoration:none; }
        .no-data-link:hover { text-decoration:underline; }

        @keyframes slideDown {
            from { transform: translateY(0); opacity: 1; }
            to { transform: translateY(-10px); opacity: 0; }
        }

        @media (max-width:768px) {
            .admin-content { padding:16px; }
            .admin-nav-link { font-size:12px; padding:8px 10px; }
            .section-header { flex-direction:column; align-items:flex-start; }
            table.data-table th, table.data-table td { padding:10px; font-size:13px; }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2><i class="fas fa-users"></i> Faculty Management</h2>
        
        <?php include 'admin_nav.php'; ?>
        
        <div class="admin-content">
            <?php if ($flash_message): ?>
                <div class="alert alert-<?php echo safe_output($flash_type); ?>">
                    <i class="fas fa-<?php echo ($flash_type === 'success') ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo safe_output($flash_message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($form_errors)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul>
                            <?php foreach ($form_errors as $error): ?>
                                <li><?php echo safe_output($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($current_page === 'form'): ?>
                <div class="content-section">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-<?php echo ($edit_id !== null) ? 'edit' : 'plus-circle'; ?>"></i>
                            <?php echo ($edit_id !== null) ? 'Edit Faculty' : 'Add New Faculty'; ?>
                        </h3>
                    </div>
                    <div class="section-body">
                        <form method="POST" class="admin-form">
                            <?php echo csrf_token_input(); ?>
                            <input type="hidden" name="action" value="<?php echo ($edit_id !== null) ? 'edit' : 'add'; ?>">
                            <?php if ($edit_id !== null): ?>
                                <input type="hidden" name="edit_id" value="<?php echo safe_output($edit_id); ?>">
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label class="form-label" for="name">Faculty Name *</label>
                                <input type="text" id="name" name="name" class="form-control" 
                                       placeholder="Enter Faculty Name (e.g., Ram Kumar Sharma)" 
                                       value="<?php echo ($edit_data) ? safe_output($edit_data['name']) : ''; ?>" 
                                       required maxlength="255">
                                <span class="form-text">Full name of the faculty member</span>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="title">Title / Designation *</label>
                                <input type="text" id="title" name="title" class="form-control" 
                                       placeholder="e.g., Principal, Physics Teacher (Exp - 15 Years)" 
                                       value="<?php echo ($edit_data) ? safe_output($edit_data['title']) : ''; ?>" 
                                       required maxlength="255">
                                <span class="form-text">Position and qualifications</span>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="image">Image Path *</label>
                                <input type="text" id="image" name="image" class="form-control" 
                                       placeholder="e.g., images/faculties/photo.jpg" 
                                       value="<?php echo ($edit_data) ? safe_output($edit_data['image']) : ''; ?>" 
                                       required maxlength="500">
                                <span class="form-text">Upload image using the Uploads section first, then copy its path here.</span>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save"></i> 
                                    <?php echo ($edit_id !== null) ? 'Update Faculty' : 'Add Faculty'; ?>
                                </button>
                                <a href="admin_faculties.php" class="btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="content-section">
                    <div class="section-header">
                        <h3><i class="fas fa-list"></i> Faculty List (<?php echo count($faculties); ?> members)</h3>
                        <a href="admin_faculties.php?add=1" class="btn-primary">
                            <i class="fas fa-plus"></i> Add Faculty
                        </a>
                    </div>
                    <div class="section-body">
                        <?php if (count($faculties) > 0): ?>
                            <div class="table-wrap">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Designation</th>
                                            <th>Image</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($faculties as $index => $faculty): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo safe_output($faculty['name']); ?></strong>
                                                </td>
                                                <td><?php echo safe_output($faculty['title']); ?></td>
                                                <td>
                                                    <?php if (!empty($faculty['image'])): ?>
                                                        <img src="<?php echo safe_output(BASE_URL . '/' . $faculty['image']); ?>" 
                                                             alt="<?php echo safe_output($faculty['name']); ?>" 
                                                             class="preview-image"
                                                             onerror="this.style.display='none'">
                                                    <?php else: ?>
                                                        <span class="no-image">No image</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="action-cell">
                                                    <a href="admin_faculties.php?edit=<?php echo $index; ?>" 
                                                       class="action-btn edit-btn" 
                                                       title="Edit Faculty"
                                                       data-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" class="inline-form" 
                                                          onsubmit="return confirm('Are you sure you want to delete this faculty member? This action cannot be undone.');">
                                                        <?php echo csrf_token_input(); ?>
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="delete_id" value="<?php echo $index; ?>">
                                                        <button type="submit" class="action-btn delete-btn" 
                                                                title="Delete Faculty"
                                                                data-toggle="tooltip">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="no-data-message">
                                <i class="fas fa-inbox no-data-icon"></i>
                                No faculty members added yet. 
                                <a href="admin_faculties.php?add=1" class="no-data-link">Add the first faculty member</a>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.animation = 'slideDown 0.3s ease reverse';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>


