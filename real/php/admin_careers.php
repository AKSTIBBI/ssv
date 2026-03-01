<?php
/**
 * Admin Panel - Career Applications Management
 * View, filter, and download job applications
 */

require_once 'auth.php';
require_once 'config.php';

// Check login
if (!isset($_SESSION[SESSION_KEY_ADMIN]) || !$_SESSION[SESSION_KEY_ADMIN]) {
    header('Location: admin_login.php');
    exit;
}

$applications_json = JSON_PATH . 'career_applications.json';
$applications = [];

// Load applications
if (file_exists($applications_json)) {
    $content = file_get_contents($applications_json);
    if (!empty($content)) {
        $applications = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $applications = [];
        }
    }
}

// reverse order
$applications = array_reverse($applications);

// CSV download
if (isset($_GET['action']) && $_GET['action'] === 'download') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="career_applications_' . date('Y-m-d_H-i-s') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, [
        'ID','Name','Email','Phone','Position','Qualification','Experience',
        'Cover Letter','Resume Link','Resume File','References','Submission Date','Status','Notes'
    ]);
    foreach ($applications as $app) {
        fputcsv($out, [
            $app['id'] ?? '',
            $app['full_name'] ?? '',
            $app['email'] ?? '',
            $app['phone'] ?? '',
            $app['position'] ?? '',
            $app['qualification'] ?? '',
            $app['experience'] ?? '',
            $app['cover_letter'] ?? '',
            $app['resume_link'] ?? '',
            $app['resume_file'] ?? '',
            $app['references'] ?? '',
            $app['submission_date'] ?? '',
            $app['status'] ?? 'new',
            $app['notes'] ?? ''
        ]);
    }
    fclose($out);
    exit;
}

// handle AJAX updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $app_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    if (empty($app_id) || empty($action)) {
        echo json_encode(['success'=>false,'message'=>'Invalid request']);
        exit;
    }
    $found=false;
    foreach ($applications as &$app) {
        if ($app['id'] == $app_id) {
            $found = true;
            if ($action === 'update_status') {
                $app['status'] = isset($_POST['status']) ? $_POST['status'] : 'new';
            } elseif ($action === 'update_notes') {
                $app['notes'] = isset($_POST['notes']) ? htmlspecialchars(trim($_POST['notes']), ENT_QUOTES, 'UTF-8') : '';
            }
            break;
        }
    }
    if ($found) {
        $rev = array_reverse($applications);
        if (file_put_contents($applications_json, json_encode($rev, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
            echo json_encode(['success'=>true,'message'=>'Updated successfully']);
        } else {
            echo json_encode(['success'=>false,'message'=>'Failed to save changes']);
        }
    } else {
        echo json_encode(['success'=>false,'message'=>'Application not found']);
    }
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Applications - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --color-primary: #244855;
            --color-primary-700: #1e3f49;
            --color-surface: #ffffff;
            --color-bg: #f3f6fa;
            --color-text: #1f3448;
            --color-muted: #5f7386;
            --color-border: #d8e1ea;
            --radius-sm: 8px;
            --radius-md: 14px;
            --shadow-sm: 0 4px 12px rgba(16, 32, 48, 0.08);
            --shadow-md: 0 12px 30px rgba(16, 32, 48, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "Poppins", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            min-height: 100vh;
            padding: 20px 14px;
        }

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .admin-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-700) 100%);
            color: #fff;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            padding: 24px;
            margin-bottom: 16px;
        }

        .admin-header h1 {
            font-size: clamp(24px, 3vw, 30px);
            margin: 0;
        }

        .admin-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0 0 18px;
            padding: 8px;
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }

        .admin-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--color-primary);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .admin-nav-link:hover {
            background: #eff4f8;
            color: var(--color-primary-700);
        }

        .admin-nav-link.active {
            background: var(--color-primary);
            color: #fff;
        }

        .admin-content {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            padding: 24px;
        }

        .btn {
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-primary);
            background: var(--color-primary);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background: var(--color-primary-700);
            border-color: var(--color-primary-700);
        }

        .empty-state {
            border: 1px dashed var(--color-border);
            border-radius: var(--radius-md);
            text-align: center;
            padding: 44px 18px;
            color: var(--color-muted);
            background: #fafcff;
        }

        .empty-state h3 {
            color: var(--color-primary);
            font-size: 24px;
        }

        .table-responsive {
            margin-top: 14px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f6f9fc;
            border-bottom: 1px solid var(--color-border);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--color-border);
            font-size: 14px;
        }

        th {
            color: var(--color-primary);
            font-weight: 600;
            white-space: nowrap;
        }

        tbody tr:hover {
            background: #f9fbfd;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 90px;
            padding: 7px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-transform: capitalize;
        }

        .status-new { background: #e3f2fd; color: #1976d2; }
        .status-contacted { background: #f3e5f5; color: #7b1fa2; }
        .status-pending { background: #fff3e0; color: #ef6c00; }
        .status-completed { background: #e8f5e9; color: #2e7d32; }
        .status-reviewed { background: #f3e5f5; color: #7b1fa2; }
        .status-shortlisted { background: #e8f5e9; color: #2e7d32; }

        .btn-logout { padding:10px 14px; background:#c62828; color:#fff; border:1px solid #c62828; border-radius:var(--radius-sm); cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-weight:600; transition:all .2s ease; }
        .btn-logout:hover { background:#a91f1f; border-color:#a91f1f; }

        .action-cell {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-small {
            padding: 8px 10px;
            background: var(--color-primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-small:hover { background: var(--color-primary-700); }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            padding: 20px;
            background: rgba(12, 26, 40, 0.55);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #fff;
            padding: 22px;
            border-radius: var(--radius-md);
            width: 100%;
            max-width: 700px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            border: 1px solid var(--color-border);
            box-shadow: var(--shadow-md);
        }

        .modal-title {
            margin: 0 0 14px;
            color: var(--color-primary);
            font-size: 20px;
        }

        .detail-row {
            display: grid;
            grid-template-columns: minmax(130px, 180px) 1fr;
            gap: 10px;
            padding: 9px 0;
            border-bottom: 1px solid var(--color-border);
        }

        .detail-row:last-child { border-bottom: none; }

        .detail-row label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.35px;
            color: var(--color-primary);
        }

        .textarea-field {
            width: 100%;
            min-height: 120px;
            padding: 10px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 14px;
        }

        .textarea-field.status-select {
            min-height: 42px;
        }

        .textarea-field:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(36, 72, 85, 0.12);
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 14px;
            flex-wrap: wrap;
        }

        .btn-muted {
            background: #6c7e90;
            border-color: #6c7e90;
        }

        .btn-muted:hover { background: #5b6c7d; border-color: #5b6c7d; }

        @media (max-width: 768px) {
            .admin-content { padding: 16px; }
            .admin-nav-link { font-size: 13px; padding: 8px 10px; }
            th, td { padding: 10px; font-size: 13px; }
            .detail-row { grid-template-columns: 1fr; gap: 4px; }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-briefcase"></i> Career Applications</h1>
        </div>
        <?php include 'admin_nav.php'; ?>
        <div class="admin-content">
            <p><a href="?action=download" class="btn">Download CSV</a></p>
            <div class="admin-header-actions">
                <a href="admin_logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
            <?php if (empty($applications)): ?>
                <div class="empty-state">
                    <h3>No applications yet</h3>
                </div>
            <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><?php echo $app['id']; ?></td>
                        <td><?php echo $app['full_name'] ?? ''; ?></td>
                        <td><?php echo $app['position'] ?? ''; ?></td>
                        <td><?php echo $app['phone'] ?? ''; ?></td>
                        <td><a href="mailto:<?php echo $app['email'] ?? ''; ?>"><?php echo $app['email'] ?? ''; ?></a></td>
                        <td><?php echo date('d M Y', strtotime($app['submission_date'])); ?></td>
                        <td>
                            <button class="status-badge status-<?php echo $app['status'] ?? 'new'; ?>" onclick="showStatusModal(<?php echo $app['id']; ?>)">
                                <?php echo ucfirst($app['status'] ?? 'new'); ?>
                            </button>
                        </td>
                        <td>
                            <div class="action-cell">
                                <button class="btn-small" onclick="showDetailsModal(<?php echo $app['id']; ?>)"><i class="fas fa-eye"></i> View</button>
                                <button class="btn-small" onclick="showNotesModal(<?php echo $app['id']; ?>)"><i class="fas fa-file-alt"></i> Notes</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        </div> <!-- .admin-content -->
    </div> <!-- .admin-container -->

    <!-- Modals (similar to admissions) -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-title">Application Details</h2>
            <div id="detailsContent"></div>
            <div class="modal-actions">
                <button class="btn" onclick="closeModal('detailsModal')">Close</button>
            </div>
        </div>
    </div>

    <div id="statusModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-title">Update Status</h2>
            <div class="detail-row">
                <label>Status:</label>
                <select id="statusSelect" class="textarea-field status-select">
                    <option value="new">New</option>
                    <option value="contacted">Contacted</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="btn" onclick="updateStatus()">Update</button>
                <button class="btn btn-muted" onclick="closeModal('statusModal')">Cancel</button>
            </div>
        </div>
    </div>

    <div id="notesModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-title">Add/Edit Notes</h2>
            <textarea id="notesContent" class="textarea-field" placeholder="Add internal notes about this application..."></textarea>
            <div class="modal-actions">
                <button class="btn" onclick="updateNotes()">Save Notes</button>
                <button class="btn btn-muted" onclick="closeModal('notesModal')">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        let currentApp = null;
        const applications = <?php echo json_encode($applications); ?>;

        function openModal(modal) { document.getElementById(modal).classList.add('active'); }
        function closeModal(modal) { document.getElementById(modal).classList.remove('active'); }
        function findApp(id) { return applications.find(a => a.id == id); }

        function showDetailsModal(id) {
            currentApp = findApp(id);
            if (!currentApp) return;
            let html = '';
            html += `<div class="detail-row"><label>Name:</label><span>${currentApp.full_name||''}</span></div>`;
            html += `<div class="detail-row"><label>Email:</label><span>${currentApp.email||''}</span></div>`;
            html += `<div class="detail-row"><label>Phone:</label><span>${currentApp.phone||''}</span></div>`;
            html += `<div class="detail-row"><label>Position:</label><span>${currentApp.position||''}</span></div>`;
            if (currentApp.other_position) {
                html += `<div class="detail-row"><label>Other Position:</label><span>${currentApp.other_position}</span></div>`;
            }
            html += `<div class="detail-row"><label>Qualification:</label><span>${currentApp.qualification||''}</span></div>`;
            html += `<div class="detail-row"><label>Experience:</label><span>${currentApp.experience||''}</span></div>`;
            html += `<div class="detail-row"><label>Cover Letter:</label><div>${currentApp.cover_letter||''}</div></div>`;
            if (currentApp.resume_link) {
                html += `<div class="detail-row"><label>Resume Link:</label><a href="${currentApp.resume_link}" target="_blank">View</a></div>`;
            }
            if (currentApp.resume_file) {
                // convert potential backslashes to forward slashes for link
                const fileUrl = '../' + currentApp.resume_file.replace(/\\\\/g, '/');
                html += `<div class="detail-row"><label>Resume File:</label><a href="${fileUrl}" target="_blank">Download</a></div>`;
            }
            if (currentApp.references) {
                html += `<div class="detail-row"><label>References:</label><div>${currentApp.references}</div></div>`;
            }
            html += `<div class="detail-row"><label>Submitted:</label><span>${currentApp.submission_date||''}</span></div>`;
            document.getElementById('detailsContent').innerHTML = html;
            openModal('detailsModal');
        }

        function showStatusModal(id) {
            currentApp = findApp(id);
            if (!currentApp) return;
            document.getElementById('statusSelect').value = currentApp.status || 'new';
            openModal('statusModal');
        }

        function updateStatus() {
            const newStatus = document.getElementById('statusSelect').value;
            fetch('', {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`id=${currentApp.id}&action=update_status&status=${newStatus}`})
                .then(r=>r.json()).then(d=>{
                    if (d.success) location.reload(); else alert(d.message);
                });
        }

        function showNotesModal(id) {
            currentApp = findApp(id);
            if (!currentApp) return;
            document.getElementById('notesContent').value = currentApp.notes||'';
            openModal('notesModal');
        }

        function updateNotes() {
            const notes = document.getElementById('notesContent').value;
            fetch('', {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`id=${currentApp.id}&action=update_notes&notes=${encodeURIComponent(notes)}`})
                .then(r=>r.json()).then(d=>{
                    if (d.success) location.reload(); else alert(d.message);
                });
        }
    </script>
</body>
</html>
