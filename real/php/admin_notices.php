<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';

// Check authentication
require_admin_login();

// Check session timeout
if (Auth::has_timed_out()) {
    redirect('admin_login.php?session=expired');
}

// Load notices
$notices = get_json_data(NOTICES_JSON, []);

// Handle POST requests (Add/Edit/Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    $action = sanitize_input($_POST['action'] ?? '');

    // ========== ADD NOTICE ==========
    if ($action === 'add') {
        $title = sanitize_input($_POST['title'] ?? '');
        $description = sanitize_input($_POST['description'] ?? '');
        $author = sanitize_input($_POST['author'] ?? '');
        $date = sanitize_input($_POST['date'] ?? '');
        $month = sanitize_input($_POST['month'] ?? '');
        $publish_date = sanitize_input($_POST['publish_date'] ?? '');

        // Validation
        if (empty($title) || empty($description)) {
            $_SESSION['error'] = 'Title and description are required';
            redirect('admin_notices.php');
        }

        if (strlen($title) > 100) {
            $_SESSION['error'] = 'Title must be less than 100 characters';
            redirect('admin_notices.php');
        }

        if (strlen($description) > 500) {
            $_SESSION['error'] = 'Description must be less than 500 characters';
            redirect('admin_notices.php');
        }

        // Create new notice
        $new_notice = [
            'notice_id' => 'notice_' . round(microtime(true) * 1000),
            'title' => $title,
            'description' => $description,
            'author' => !empty($author) ? $author : get_current_admin_name(),
            'date' => !empty($date) ? $date : date('d'),
            'month' => !empty($month) ? $month : date('M'),
            'publish_date' => !empty($publish_date) ? $publish_date : date('d-M-Y'),
            'deleted' => false
        ];

        // Add to beginning for newest first
        array_unshift($notices, $new_notice);

        // Save
        if (save_json_file(NOTICES_JSON, $notices)) {
            $_SESSION['success'] = 'Notice added successfully!';
        } else {
            $_SESSION['error'] = 'Failed to save notice. Check file permissions.';
        }
        redirect('admin_notices.php');
    }

    // ========== EDIT NOTICE ==========
    elseif ($action === 'edit') {
        $notice_id = sanitize_input($_POST['notice_id'] ?? '');
        $title = sanitize_input($_POST['title'] ?? '');
        $description = sanitize_input($_POST['description'] ?? '');
        $author = sanitize_input($_POST['author'] ?? '');
        $date = sanitize_input($_POST['date'] ?? '');
        $month = sanitize_input($_POST['month'] ?? '');
        $publish_date = sanitize_input($_POST['publish_date'] ?? '');

        // Validation
        if (empty($notice_id) || empty($title) || empty($description)) {
            $_SESSION['error'] = 'All fields are required';
            redirect('admin_notices.php');
        }

        if (strlen($title) > 100) {
            $_SESSION['error'] = 'Title must be less than 100 characters';
            redirect('admin_notices.php');
        }

        if (strlen($description) > 500) {
            $_SESSION['error'] = 'Description must be less than 500 characters';
            redirect('admin_notices.php');
        }

        // Find and update
        $found = false;
        foreach ($notices as &$notice) {
            if ($notice['notice_id'] === $notice_id) {
                $notice['title'] = $title;
                $notice['description'] = $description;
                $notice['author'] = !empty($author) ? $author : $notice['author'];
                $notice['date'] = !empty($date) ? $date : $notice['date'];
                $notice['month'] = !empty($month) ? $month : $notice['month'];
                $notice['publish_date'] = !empty($publish_date) ? $publish_date : $notice['publish_date'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['error'] = 'Notice not found';
            redirect('admin_notices.php');
        }

        // Save
        if (save_json_file(NOTICES_JSON, $notices)) {
            $_SESSION['success'] = 'Notice updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update notice. Check file permissions.';
        }
        redirect('admin_notices.php');
    }

    // ========== DELETE NOTICE ==========
    elseif ($action === 'delete') {
        $notice_id = sanitize_input($_POST['notice_id'] ?? '');

        if (empty($notice_id)) {
            $_SESSION['error'] = 'Notice ID is required';
            redirect('admin_notices.php');
        }

        // Find and mark as deleted
        $found = false;
        foreach ($notices as &$notice) {
            if ($notice['notice_id'] === $notice_id) {
                $notice['deleted'] = true;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['error'] = 'Notice not found';
            redirect('admin_notices.php');
        }

        // Save
        if (save_json_file(NOTICES_JSON, $notices)) {
            $_SESSION['success'] = 'Notice deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete notice. Check file permissions.';
        }
        redirect('admin_notices.php');
    }

    // ========== RESTORE NOTICE ==========
    elseif ($action === 'restore') {
        $notice_id = sanitize_input($_POST['notice_id'] ?? '');

        if (empty($notice_id)) {
            $_SESSION['error'] = 'Notice ID is required';
            redirect('admin_notices.php');
        }

        // Find and restore
        $found = false;
        foreach ($notices as &$notice) {
            if ($notice['notice_id'] === $notice_id) {
                $notice['deleted'] = false;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['error'] = 'Notice not found';
            redirect('admin_notices.php');
        }

        // Save
        if (save_json_file(NOTICES_JSON, $notices)) {
            $_SESSION['success'] = 'Notice restored successfully!';
        } else {
            $_SESSION['error'] = 'Failed to restore notice. Check file permissions.';
        }
        redirect('admin_notices.php');
    }
}

// Filter out deleted notices for display
$active_notices = array_filter($notices, function($n) { return !$n['deleted']; });
$deleted_notices = array_filter($notices, function($n) { return $n['deleted']; });
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices Management - Admin Dashboard</title>
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
        .admin-content { background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); overflow:hidden; }
        .tabs-header { display:flex; flex-wrap:wrap; gap:8px; background:#f6f9fc; border-bottom:1px solid var(--color-border); padding:10px; }
        .btn-logout { padding:10px 14px; background:#c62828; color:#fff; border:1px solid #c62828; border-radius:var(--radius-sm); cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-weight:600; transition:all .2s ease; }
        .btn-logout:hover { background:#a91f1f; border-color:#a91f1f; }
        .tab-btn { padding:10px 12px; cursor:pointer; background:transparent; border:1px solid transparent; color:var(--color-muted); font-weight:600; font-size:14px; border-radius:var(--radius-sm); transition:all .2s ease; text-align:center; }
        .tab-btn.active { color:#fff; background:var(--color-primary); }
        .tab-btn:hover { background:#e8eff5; color:var(--color-primary); }
        .tab-content { display:none; padding:22px; }
        .tab-content.active { display:block; }

        .section-title { color:var(--color-primary); margin-bottom:16px; font-size:22px; display:inline-flex; align-items:center; gap:8px; }
        .form-group { margin-bottom:14px; }
        .form-group label { display:block; margin-bottom:6px; color:var(--color-primary); font-weight:600; font-size:14px; }
        .form-group input, .form-group textarea, .form-group select { width:100%; padding:10px; border:1px solid var(--color-border); border-radius:var(--radius-sm); font-size:14px; font-family:inherit; color:var(--color-text); background:#fff; }
        .form-group textarea { resize:vertical; min-height:100px; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline:none; border-color:var(--color-primary); box-shadow:0 0 0 3px rgba(36,72,85,.12); }
        .help-text { color:var(--color-muted); font-size:12px; }
        .caps-input { text-transform: uppercase; }

        .form-row { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; }
        .btn-group { display:flex; gap:10px; margin-top:16px; flex-wrap:wrap; }
        .btn { padding:10px 14px; border:1px solid transparent; border-radius:var(--radius-sm); cursor:pointer; font-weight:600; font-size:14px; transition:all .2s ease; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
        .btn-primary { background:var(--color-primary); color:#fff; border-color:var(--color-primary); }
        .btn-primary:hover { background:var(--color-primary-700); border-color:var(--color-primary-700); }
        .btn-secondary { background:#6c7e90; color:#fff; border-color:#6c7e90; }
        .btn-secondary:hover { background:#5b6c7d; border-color:#5b6c7d; }
        .btn-danger { background:#c62828; color:#fff; border-color:#c62828; }
        .btn-danger:hover { background:#a91f1f; border-color:#a91f1f; }
        .btn-success { background:#1f7a44; color:#fff; border-color:#1f7a44; }
        .btn-success:hover { background:#166437; border-color:#166437; }
        .btn-sm { padding:8px 10px; font-size:12px; }

        .message { padding:12px 14px; border-radius:var(--radius-sm); margin:14px; display:flex; gap:10px; align-items:center; }
        .message.success { background:#e9f8ef; color:#0b6b3c; border:1px solid #9ad7b4; }
        .message.error { background:#fff6f6; color:#8f1f1f; border:1px solid #f4b6b6; }

        .table-wrap { border:1px solid var(--color-border); border-radius:var(--radius-md); overflow-x:auto; }
        .notice-table { width:100%; border-collapse:collapse; }
        .notice-table thead { background:#f6f9fc; border-bottom:1px solid var(--color-border); }
        .notice-table th, .notice-table td { padding:12px; text-align:left; border-bottom:1px solid var(--color-border); font-size:14px; }
        .notice-table th { color:var(--color-primary); font-weight:600; }
        .notice-table tbody tr:hover { background:#f9fbfd; }
        .notice-title { font-weight:600; color:var(--color-text); }
        .notice-desc { color:var(--color-muted); }
        .notice-meta { color:var(--color-text); font-size:13px; }
        .action-buttons { display:flex; justify-content:center; gap:8px; flex-wrap:wrap; }
        .inline-form { display:inline; }
        .is-deleted-row { opacity:.6; }

        .empty-state { text-align:center; padding:38px 16px; color:var(--color-muted); border:1px dashed var(--color-border); border-radius:var(--radius-md); background:#fbfdff; }
        .empty-state i { font-size:44px; margin-bottom:12px; display:block; color:#90a4b5; }
        .empty-state h4 { color:var(--color-primary); margin-bottom:6px; }

        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(12,26,40,.55); z-index:1000; overflow-y:auto; padding:24px 12px; }
        .modal-card { background:#fff; margin:40px auto; padding:22px; border-radius:var(--radius-md); max-width:700px; width:100%; border:1px solid var(--color-border); box-shadow:0 12px 30px rgba(16,32,48,.2); }
        .modal-title { color:var(--color-primary); margin-bottom:16px; font-size:22px; display:inline-flex; align-items:center; gap:8px; }

        @media (max-width: 900px) { .form-row { grid-template-columns:1fr; } }
        @media (max-width: 768px) {
            .admin-container h2 { padding:18px; }
            .tab-content { padding:16px; }
            .admin-nav-link { font-size:12px; padding:8px 10px; }
            .notice-table th, .notice-table td { padding:10px; font-size:13px; }
        }
    </style>
</head>
<body>
        <div class="admin-container">
            <div class="admin-header">
                <div>
                    <h2><i class="fas fa-bullhorn"></i> Notices & Events Management</h2>
                </div>
                <div class="admin-header-actions">
                <a href="admin_logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                </div>
            </div>
        
        <?php include 'admin_nav.php'; ?>

        <div class="admin-content">
            <!-- Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="message success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Tabs -->
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab('add-notice', this)"><i class="fas fa-plus"></i> Add Notice</button>
                <button class="tab-btn" onclick="switchTab('active-notices', this)"><i class="fas fa-list"></i> Active Notices (<?php echo count($active_notices); ?>)</button>
                <button class="tab-btn" onclick="switchTab('deleted-notices', this)"><i class="fas fa-trash"></i> Trash (<?php echo count($deleted_notices); ?>)</button>
            </div>

            <!-- TAB 1: ADD NOTICE -->
            <div id="add-notice" class="tab-content active">
                <h3 class="section-title"><i class="fas fa-plus-circle"></i> Add New Notice/Event</h3>
                
                <form method="POST" action="admin_notices.php">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="form-group">
                        <label for="title"><i class="fas fa-heading"></i> Notice Title *</label>
                        <input type="text" id="title" name="title" placeholder="e.g., Annual Sports Day, Holi Holiday, Parent-Teacher Meeting" required maxlength="100">
                        <small class="help-text">(Max 100 characters)</small>
                    </div>

                    <div class="form-group">
                        <label for="description"><i class="fas fa-align-left"></i> Description *</label>
                        <textarea id="description" name="description" placeholder="Provide detailed information about the notice or event" required maxlength="500"></textarea>
                        <small class="help-text">(Max 500 characters)</small>
                    </div>

                    <div class="form-group">
                        <label for="author"><i class="fas fa-user"></i> Author Name</label>
                        <input type="text" id="author" name="author" placeholder="e.g., Principal, Director, Event Coordinator (Optional - defaults to logged-in admin)">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="date"><i class="fas fa-calendar-alt"></i> Date</label>
                            <input type="text" id="date" name="date" placeholder="e.g., 27" maxlength="2" pattern="[0-9]{1,2}">
                            <small class="help-text">Day number (1-31)</small>
                        </div>
                        <div class="form-group">
                            <label for="month"><i class="fas fa-calendar"></i> Month</label>
                            <input type="text" id="month" name="month" class="caps-input" placeholder="e.g., Feb, Mar" maxlength="3">
                            <small class="help-text">Month abbreviation</small>
                        </div>
                        <div class="form-group">
                            <label for="publish_date"><i class="fas fa-clock"></i> Publish Date</label>
                            <input type="text" id="publish_date" name="publish_date" placeholder="e.g., 27-Feb-2026">
                            <small class="help-text">Full date format</small>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Notice</button>
                        <button type="reset" class="btn btn-secondary"><i class="fas fa-redo"></i> Clear Form</button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: ACTIVE NOTICES -->
            <div id="active-notices" class="tab-content">
                <h3 class="section-title"><i class="fas fa-list"></i> Active Notices & Events</h3>
                
                <?php if (empty($active_notices)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h4>No Active Notices</h4>
                        <p>Add your first notice using the "Add Notice" tab.</p>
                    </div>
                <?php else: ?>
                    <div class="table-wrap">
                    <table class="notice-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-heading"></i> Title</th>
                                <th><i class="fas fa-align-left"></i> Description</th>
                                <th><i class="fas fa-user"></i> Author</th>
                                <th><i class="fas fa-calendar"></i> Date</th>
                                <th><i class="fas fa-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($active_notices as $notice): ?>
                                <tr>
                                    <td><span class="notice-title"><?php echo htmlspecialchars($notice['title']); ?></span></td>
                                    <td><span class="notice-desc"><?php echo htmlspecialchars(substr($notice['description'], 0, 50)) . '...'; ?></span></td>
                                    <td><span class="notice-meta"><?php echo htmlspecialchars($notice['author'] ?? 'Admin'); ?></span></td>
                                    <td><span class="notice-meta"><?php echo htmlspecialchars($notice['date'] . ' ' . $notice['month']); ?></span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-primary btn-sm" onclick="editNotice(<?php echo htmlspecialchars(json_encode($notice)); ?>)"><i class="fas fa-edit"></i> Edit</button>
                                            <form method="POST" action="admin_notices.php" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this notice?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="notice_id" value="<?php echo htmlspecialchars($notice['notice_id']); ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TAB 3: DELETED NOTICES -->
            <div id="deleted-notices" class="tab-content">
                <h3 class="section-title"><i class="fas fa-trash"></i> Deleted Notices (Trash)</h3>
                
                <?php if (empty($deleted_notices)): ?>
                    <div class="empty-state">
                        <i class="fas fa-trash-alt"></i>
                        <h4>Trash is Empty</h4>
                        <p>Deleted notices will appear here.</p>
                    </div>
                <?php else: ?>
                    <div class="table-wrap">
                    <table class="notice-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-heading"></i> Title</th>
                                <th><i class="fas fa-align-left"></i> Description</th>
                                <th><i class="fas fa-user"></i> Author</th>
                                <th><i class="fas fa-calendar"></i> Date</th>
                                <th><i class="fas fa-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deleted_notices as $notice): ?>
                                <tr class="is-deleted-row">
                                    <td><span class="notice-title"><?php echo htmlspecialchars($notice['title']); ?></span></td>
                                    <td><span class="notice-desc"><?php echo htmlspecialchars(substr($notice['description'], 0, 50)) . '...'; ?></span></td>
                                    <td><span class="notice-meta"><?php echo htmlspecialchars($notice['author'] ?? 'Admin'); ?></span></td>
                                    <td><span class="notice-meta"><?php echo htmlspecialchars($notice['date'] . ' ' . $notice['month']); ?></span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <form method="POST" action="admin_notices.php" class="inline-form" onsubmit="return confirm('Restore this notice?');">
                                                <input type="hidden" name="action" value="restore">
                                                <input type="hidden" name="notice_id" value="<?php echo htmlspecialchars($notice['notice_id']); ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-undo"></i> Restore</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Notice Modal -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-card">
            <h3 class="modal-title"><i class="fas fa-edit"></i> Edit Notice</h3>
            <form method="POST" action="admin_notices.php" id="editForm">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="notice_id" id="editNoticeId">

                <div class="form-group">
                    <label for="editTitle"><i class="fas fa-heading"></i> Notice Title *</label>
                    <input type="text" id="editTitle" name="title" required maxlength="100">
                </div>

                <div class="form-group">
                    <label for="editDescription"><i class="fas fa-align-left"></i> Description *</label>
                    <textarea id="editDescription" name="description" required maxlength="500"></textarea>
                </div>

                <div class="form-group">
                    <label for="editAuthor"><i class="fas fa-user"></i> Author Name</label>
                    <input type="text" id="editAuthor" name="author">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editDate"><i class="fas fa-calendar-alt"></i> Date</label>
                        <input type="text" id="editDate" name="date" maxlength="2">
                    </div>
                    <div class="form-group">
                        <label for="editMonth"><i class="fas fa-calendar"></i> Month</label>
                        <input type="text" id="editMonth" name="month" maxlength="3">
                    </div>
                    <div class="form-group">
                        <label for="editPublishDate"><i class="fas fa-clock"></i> Publish Date</label>
                        <input type="text" id="editPublishDate" name="publish_date">
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Notice</button>
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()"><i class="fas fa-times"></i> Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tabName, triggerBtn) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            if (triggerBtn) {
                triggerBtn.classList.add('active');
            }
        }

        function editNotice(notice) {
            document.getElementById('editNoticeId').value = notice.notice_id;
            document.getElementById('editTitle').value = notice.title;
            document.getElementById('editDescription').value = notice.description;
            document.getElementById('editAuthor').value = notice.author || '';
            document.getElementById('editDate').value = notice.date || '';
            document.getElementById('editMonth').value = notice.month || '';
            document.getElementById('editPublishDate').value = notice.publish_date || '';
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>

