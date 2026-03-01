<?php
/**
 * Admin Panel - Admission Enquiries Management
 * View, filter, and download admission enquiries
 */

require_once 'auth.php';
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION[SESSION_KEY_ADMIN]) || !$_SESSION[SESSION_KEY_ADMIN]) {
    header('Location: admin_login.php');
    exit;
}

$enquiries_json = JSON_PATH . 'admission_enquiries.json';
$enquiries = [];

// Load enquiries
if (file_exists($enquiries_json)) {
    $content = file_get_contents($enquiries_json);
    if (!empty($content)) {
        $enquiries = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $enquiries = [];
        }
    }
}

// Reverse to show newer enquiries first
$enquiries = array_reverse($enquiries);

// Handle CSV download
if (isset($_GET['action']) && $_GET['action'] === 'download') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="admission_enquiries_' . date('Y-m-d_H-i-s') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // CSV Header
    fputcsv($output, [
        'ID', 'Student Name', 'Date of Birth', 'Gender', 'Class', 'Parent/Guardian',
        'Relationship', 'Contact Number', 'Email', 'Address', 'Previous School',
        'Additional Info', 'Submission Date', 'Status', 'Notes'
    ]);
    
    // CSV Data
    foreach ($enquiries as $enquiry) {
        fputcsv($output, [
            $enquiry['id'] ?? '',
            $enquiry['student_name'] ?? '',
            $enquiry['date_of_birth'] ?? '',
            $enquiry['gender'] ?? '',
            $enquiry['applying_class'] ?? '',
            $enquiry['parent_name'] ?? '',
            $enquiry['relationship'] ?? '',
            $enquiry['contact_number'] ?? '',
            $enquiry['email'] ?? '',
            $enquiry['address'] ?? '',
            $enquiry['previous_school'] ?? '',
            $enquiry['additional_info'] ?? '',
            $enquiry['submission_date'] ?? '',
            $enquiry['status'] ?? 'new',
            $enquiry['notes'] ?? ''
        ]);
    }
    
    fclose($output);
    exit;
}

// Handle AJAX status and notes update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $enquiry_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if (empty($enquiry_id) || empty($action)) {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
    
    // Find and update enquiry
    $found = false;
    foreach ($enquiries as &$enquiry) {
        if ($enquiry['id'] == $enquiry_id) {
            $found = true;
            if ($action === 'update_status') {
                $enquiry['status'] = isset($_POST['status']) ? $_POST['status'] : 'new';
            } elseif ($action === 'update_notes') {
                $enquiry['notes'] = isset($_POST['notes']) ? htmlspecialchars(trim($_POST['notes']), ENT_QUOTES, 'UTF-8') : '';
            }
            break;
        }
    }
    
    if ($found) {
        // Reverse back to original order for saving
        $enquiries_reversed = array_reverse($enquiries);
        if (file_put_contents($enquiries_json, json_encode($enquiries_reversed, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
            echo json_encode(['success' => true, 'message' => 'Updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save changes']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Enquiry not found']);
    }
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Enquiries - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/01_variables.css">
    <link rel="stylesheet" href="../css/02_base.css">
    <link rel="stylesheet" href="../css/03_layout.css">
    <link rel="stylesheet" href="../css/04_components.css">
    <link rel="stylesheet" href="../css/05_utils.css">
    <style>
        body {
            background: var(--color-bg);
        }

        .admin-container {
            max-width: 1400px;
            margin: var(--space-4) auto;
            padding: var(--space-4);
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: var(--space-3);
            margin-bottom: var(--space-5);
            padding: var(--space-4);
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-700) 100%);
            border-radius: var(--radius-md);
            color: #fff;
            box-shadow: var(--shadow-sm);
        }

        .admin-header h1 {
            color: #fff;
            font-size: clamp(22px, 3vw, 30px);
            margin: 0;
        }

        .admin-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0 0 var(--space-5);
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
            font-size: var(--text-sm);
            font-weight: 500;
            transition: all var(--transition-fast);
        }

        .admin-nav-link:hover {
            background: #eff4f8;
            color: var(--color-primary-700);
        }

        .admin-nav-link.active {
            background: var(--color-primary);
            color: #fff;
        }

        .btn-group {
            display: flex;
            gap: var(--space-2);
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 14px;
            border: 1px solid transparent;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            font-size: var(--text-sm);
            line-height: 1.2;
        }

        .btn-primary {
            background: #fff;
            color: var(--color-primary);
            border-color: #fff;
        }

        .btn-primary:hover {
            background: #f1f5f8;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.4);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.22);
        }

        .btn-logout { padding:10px 14px; background:#c62828; color:#fff; border:1px solid #c62828; border-radius:var(--radius-sm); cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-weight:600; transition:all .2s ease; }
        .btn-logout:hover { background:#a91f1f; border-color:#a91f1f; }
 
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--space-4);
            margin-bottom: var(--space-6);
        }

        .stat-card {
            background: var(--color-surface);
            padding: var(--space-4);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border);
            box-shadow: var(--shadow-sm);
        }

        .stat-label {
            color: var(--color-muted);
            font-size: var(--text-sm);
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .stat-value {
            font-size: clamp(24px, 3vw, 34px);
            font-weight: 700;
            color: var(--color-primary);
            margin-top: var(--space-2);
            line-height: 1.1;
        }

        .empty-state {
            background: var(--color-surface);
            border: 1px dashed var(--color-border);
            border-radius: var(--radius-md);
            padding: var(--space-7) var(--space-4);
            text-align: center;
            color: var(--color-muted);
        }

        .empty-state .fa-inbox {
            font-size: 42px;
            margin-bottom: var(--space-3);
            color: #90a4b5;
        }

        .empty-state h3 {
            margin-bottom: var(--space-2);
            color: var(--color-primary);
        }

        .table-responsive {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            overflow-x: auto;
            box-shadow: var(--shadow-sm);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f6f9fc;
            border-bottom: 1px solid var(--color-border);
        }

        th {
            padding: var(--space-3);
            text-align: left;
            color: var(--color-primary);
            font-weight: 600;
            font-size: var(--text-sm);
            white-space: nowrap;
        }

        td {
            padding: var(--space-3);
            border-bottom: 1px solid var(--color-border);
            color: var(--color-text);
            vertical-align: middle;
            font-size: var(--text-sm);
        }

        tbody tr:hover {
            background: #f9fbfd;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        td a {
            color: var(--color-primary);
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
            text-transform: capitalize;
            border: none;
            cursor: pointer;
            transition: transform var(--transition-fast);
        }

        .status-badge:hover {
            transform: translateY(-1px);
        }

        .status-new { background: #e3f2fd; color: #1976d2; }
        .status-contacted { background: #f3e5f5; color: #7b1fa2; }
        .status-pending { background: #fff3e0; color: #ef6c00; }
        .status-completed { background: #e8f5e9; color: #2e7d32; }
        .status-processed { background: #fff3e0; color: #ef6c00; }
        .status-enrolled { background: #e8f5e9; color: #2e7d32; }

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
            transition: all var(--transition-fast);
        }

        .btn-small:hover {
            background: var(--color-primary-700);
        }

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
            padding: var(--space-5);
            border-radius: var(--radius-md);
            width: 100%;
            max-width: 640px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--color-border);
        }

        .modal-title {
            margin: 0 0 var(--space-4);
            color: var(--color-primary);
            font-size: var(--text-lg);
        }

        .detail-row {
            display: grid;
            grid-template-columns: minmax(130px, 180px) 1fr;
            gap: var(--space-3);
            padding: 10px 0;
            border-bottom: 1px solid var(--color-border);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-row label {
            font-weight: 600;
            color: var(--color-primary);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.35px;
            margin: 0;
        }

        .detail-row span {
            color: var(--color-text);
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .textarea-field {
            width: 100%;
            padding: var(--space-2);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: var(--text-sm);
            min-height: 120px;
        }

        .textarea-field:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(36, 72, 85, 0.12);
        }

        .textarea-field.status-select {
            min-height: 42px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: var(--space-2);
            margin-top: var(--space-4);
        }

        .btn-muted {
            background: #6c7e90;
            border-color: #6c7e90;
            color: #fff;
        }

        .btn-muted:hover {
            background: #5b6c7d;
        }

        @media (max-width: 900px) {
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-group {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: var(--space-3);
            }

            .admin-nav {
                gap: 6px;
                padding: 6px;
            }

            .admin-nav-link {
                padding: 8px 10px;
                font-size: 13px;
            }

            th, td {
                padding: 10px;
                font-size: 13px;
            }

            .detail-row {
                grid-template-columns: 1fr;
                gap: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-graduation-cap"></i> Admission Enquiries</h1>
            <div class="btn-group">
                <a href="admin_dashboard.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="?action=download" class="btn btn-secondary">
                    <i class="fas fa-download"></i> Download CSV
                </a>
            </div>
            <div class="admin-header-actions">
                <a href="admin_logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        <?php include 'admin_nav.php'; ?>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Total Enquiries</div>
                <div class="stat-value"><?php echo count($enquiries); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">New Enquiries</div>
                <div class="stat-value">
                    <?php 
                    $new_count = count(array_filter($enquiries, function($e) { 
                        return ($e['status'] ?? 'new') === 'new'; 
                    }));
                    echo $new_count;
                    ?>
                </div>
            </div>
        </div>
        
        <?php if (empty($enquiries)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Admission Enquiries Yet</h3>
                <p>Enquiries will appear here when students submit the admission form.</p>
            </div>
        <?php else: ?>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Parent/Guardian</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enquiries as $enquiry): ?>
                    <tr>
                        <td><?php echo $enquiry['id']; ?></td>
                        <td><?php echo $enquiry['student_name'] ?? ''; ?></td>
                        <td><?php echo $enquiry['applying_class'] ?? ''; ?></td>
                        <td><?php echo $enquiry['parent_name'] ?? ''; ?></td>
                        <td><?php echo $enquiry['contact_number'] ?? ''; ?></td>
                        <td><a href="mailto:<?php echo $enquiry['email'] ?? ''; ?>"><?php echo $enquiry['email'] ?? ''; ?></a></td>
                        <td><?php echo date('d M Y', strtotime($enquiry['submission_date'])); ?></td>
                        <td>
                            <button class="status-badge status-<?php echo $enquiry['status'] ?? 'new'; ?>" 
                                    onclick="showStatusModal(<?php echo $enquiry['id']; ?>)">
                                <?php echo ucfirst($enquiry['status'] ?? 'new'); ?>
                            </button>
                        </td>
                        <td>
                            <div class="action-cell">
                                <button class="btn-small" onclick="showDetailsModal(<?php echo $enquiry['id']; ?>)">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn-small" onclick="showNotesModal(<?php echo $enquiry['id']; ?>)">
                                    <i class="fas fa-file-alt"></i> Notes
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php endif; ?>
    </div>
    
    <!-- Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-title">Enquiry Details</h2>
            <div id="detailsContent"></div>
            <div class="modal-actions">
                <button class="btn btn-primary" onclick="closeModal('detailsModal')">Close</button>
            </div>
        </div>
    </div>
    
    <!-- Status Modal -->
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
                <button class="btn btn-primary" onclick="updateStatus()">Update</button>
                <button class="btn btn-primary btn-muted" onclick="closeModal('statusModal')">Cancel</button>
            </div>
        </div>
    </div>
    
    <!-- Notes Modal -->
    <div id="notesModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-title">Add/Edit Notes</h2>
            <textarea id="notesContent" class="textarea-field" placeholder="Add internal notes about this enquiry..."></textarea>
            <div class="modal-actions">
                <button class="btn btn-primary" onclick="updateNotes()">Save Notes</button>
                <button class="btn btn-primary btn-muted" onclick="closeModal('notesModal')">Cancel</button>
            </div>
        </div>
    </div>
    
    <script>
        let currentEnquiry = null;
        const enquiries = <?php echo json_encode($enquiries); ?>;
        
        function openModal(modal) {
            document.getElementById(modal).classList.add('active');
        }
        
        function closeModal(modal) {
            document.getElementById(modal).classList.remove('active');
        }
        
        function findEnquiry(id) {
            return enquiries.find(e => e.id == id);
        }
        
        function showDetailsModal(id) {
            currentEnquiry = findEnquiry(id);
            if (!currentEnquiry) return;
            
            const details = `
                <div class="detail-row">
                    <label>Student Name:</label>
                    <span>${currentEnquiry.student_name || ''}</span>
                </div>
                <div class="detail-row">
                    <label>Date of Birth:</label>
                    <span>${currentEnquiry.date_of_birth || ''}</span>
                </div>
                <div class="detail-row">
                    <label>Gender:</label>
                    <span>${currentEnquiry.gender || ''}</span>
                </div>
                <div class="detail-row">
                    <label>Applying Class:</label>
                    <span>${currentEnquiry.applying_class || ''}</span>
                </div>
                <div class="detail-row">
                    <label>Parent/Guardian:</label>
                    <span>${currentEnquiry.parent_name || ''}</span>
                </div>
                <div class="detail-row">
                    <label>Relationship:</label>
                    <span>${currentEnquiry.relationship || ''}</span>
                </div>
                <div class="detail-row">
                    <label>Contact Number:</label>
                    <span>${currentEnquiry.contact_number || ''}</span>
                </div>
                <div class="detail-row">
                    <label>Email:</label>
                    <span><a href="mailto:${currentEnquiry.email || ''}">${currentEnquiry.email || ''}</a></span>
                </div>
                <div class="detail-row">
                    <label>Address:</label>
                    <span>${currentEnquiry.address || ''}</span>
                </div>
                ${currentEnquiry.previous_school ? `
                <div class="detail-row">
                    <label>Previous School:</label>
                    <span>${currentEnquiry.previous_school}</span>
                </div>
                ` : ''}
                ${currentEnquiry.additional_info ? `
                <div class="detail-row">
                    <label>Additional Info:</label>
                    <span>${currentEnquiry.additional_info}</span>
                </div>
                ` : ''}
                <div class="detail-row">
                    <label>Submitted:</label>
                    <span>${new Date(currentEnquiry.submission_date).toLocaleString()}</span>
                </div>
            `;
            
            document.getElementById('detailsContent').innerHTML = details;
            openModal('detailsModal');
        }
        
        function showStatusModal(id) {
            currentEnquiry = findEnquiry(id);
            if (!currentEnquiry) return;
            
            document.getElementById('statusSelect').value = currentEnquiry.status || 'new';
            openModal('statusModal');
        }
        
        function updateStatus() {
            if (!currentEnquiry) return;
            
            const formData = new FormData();
            formData.append('id', currentEnquiry.id);
            formData.append('action', 'update_status');
            formData.append('status', document.getElementById('statusSelect').value);
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated successfully');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
        
        function showNotesModal(id) {
            currentEnquiry = findEnquiry(id);
            if (!currentEnquiry) return;
            
            document.getElementById('notesContent').value = currentEnquiry.notes || '';
            openModal('notesModal');
        }
        
        function updateNotes() {
            if (!currentEnquiry) return;
            
            const formData = new FormData();
            formData.append('id', currentEnquiry.id);
            formData.append('action', 'update_notes');
            formData.append('notes', document.getElementById('notesContent').value);
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Notes saved successfully');
                    closeModal('notesModal');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const detailsModal = document.getElementById('detailsModal');
            const statusModal = document.getElementById('statusModal');
            const notesModal = document.getElementById('notesModal');
            
            if (event.target == detailsModal) detailsModal.classList.remove('active');
            if (event.target == statusModal) statusModal.classList.remove('active');
            if (event.target == notesModal) notesModal.classList.remove('active');
        }
    </script>
</body>
</html>

