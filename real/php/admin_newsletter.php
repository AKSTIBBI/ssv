<?php
/**
 * Admin Panel - Newsletter Subscribers Management
 * View and manage newsletter subscribers
 */

require_once 'auth.php';
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION[SESSION_KEY_ADMIN]) || !$_SESSION[SESSION_KEY_ADMIN]) {
    header('Location: admin_login.php');
    exit;
}

$newsletter_json = JSON_PATH . 'newsletter_subscribers.json';
$subscribers = [];

// Load subscribers
if (file_exists($newsletter_json)) {
    $content = file_get_contents($newsletter_json);
    if (!empty($content)) {
        $subscribers = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $subscribers = [];
        }
    }
}

// Reverse to show newer subscribers first
$subscribers = array_reverse($subscribers);

// Handle CSV download
if (isset($_GET['action']) && $_GET['action'] === 'download') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="newsletter_subscribers_' . date('Y-m-d_H-i-s') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // CSV Header
    fputcsv($output, ['ID', 'Email', 'Subscribed Date', 'Status']);
    
    // CSV Data - need to reverse back
    $reversed = array_reverse($subscribers);
    foreach ($reversed as $subscriber) {
        fputcsv($output, [
            $subscriber['id'] ?? '',
            $subscriber['email'] ?? '',
            $subscriber['subscribed_date'] ?? '',
            $subscriber['status'] ?? 'active'
        ]);
    }
    
    fclose($output);
    exit;
}

// Handle AJAX delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $subscriber_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if (empty($subscriber_id) || empty($action)) {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
    
    // Find and remove subscriber
    $found = false;
    $reversed = array_reverse($subscribers);
    foreach ($reversed as $key => &$subscriber) {
        if ($subscriber['id'] == $subscriber_id) {
            if ($action === 'unsubscribe') {
                unset($reversed[$key]);
            }
            $found = true;
            break;
        }
    }
    
    if ($found && $action === 'unsubscribe') {
        // Re-reverse back to original order
        $subscribers = array_reverse($reversed);
        // Re-index array
        $subscribers = array_values($subscribers);
        if (file_put_contents($newsletter_json, json_encode($subscribers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
            echo json_encode(['success' => true, 'message' => 'Subscriber removed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove subscriber']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Subscriber not found']);
    }
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Subscribers - Admin Panel</title>
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
            max-width: 1200px;
            margin: var(--space-4) auto;
            padding: var(--space-4);
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-6);
            padding-bottom: var(--space-4);
            border-bottom: 2px solid var(--color-border);
        }
        
        .admin-header h1 {
            color: var(--color-primary);
            font-size: var(--text-2xl);
        }
        
        .btn-group {
            display: flex;
            gap: var(--space-2);
        }
        
        .btn {
            padding: var(--space-2) var(--space-4);
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: var(--space-1);
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--color-primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #1a3642;
        }
        
        .btn-secondary {
            background: var(--color-secondary);
            color: var(--color-primary);
        }
        
        .btn-secondary:hover {
            background: #ddd;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--space-4);
            margin-bottom: var(--space-6);
        }
        
        .stat-card {
            background: white;
            padding: var(--space-4);
            border-radius: var(--radius-md);
            border-left: 4px solid var(--color-primary);
            box-shadow: var(--shadow-sm);
        }
        
        .stat-label {
            color: var(--color-muted);
            font-size: var(--text-sm);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-value {
            font-size: var(--text-3xl);
            font-weight: 700;
            color: var(--color-primary);
            margin-top: var(--space-2);
        }
        
        .table-responsive {
            background: white;
            border-radius: var(--radius-md);
            overflow-x: auto;
            box-shadow: var(--shadow-sm);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: var(--color-bg);
            border-bottom: 2px solid var(--color-border);
        }
        
        th {
            padding: var(--space-3);
            text-align: left;
            color: var(--color-primary);
            font-weight: 600;
        }
        
        td {
            padding: var(--space-3);
            border-bottom: 1px solid var(--color-border);
        }
        
        tbody tr:hover {
            background: var(--color-bg);
        }
        
        .action-btn {
            padding: var(--space-1) var(--space-2);
            margin: 0 var(--space-1);
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all var(--transition-fast);
        }
        
        .action-btn:hover {
            background: #1a3642;
        }
        
        .message {
            padding: var(--space-3);
            border-radius: var(--radius-sm);
            margin-bottom: var(--space-4);
            display: flex;
            align-items: center;
            gap: var(--space-2);
            animation: slideIn 0.3s ease-in;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .btn-group {
                width: 100%;
                margin-top: var(--space-2);
            }
            
            table {
                font-size: var(--text-xs);
            }
            
            th, td {
                padding: var(--space-2);
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-envelope"></i> Newsletter Subscribers</h1>
            <div class="btn-group">
                <a href="admin_dashboard.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="?action=download" class="btn btn-secondary">
                    <i class="fas fa-download"></i> Download CSV
                </a>
            </div>
        </div>
        <?php include 'admin_nav.php'; ?>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Total Subscribers</div>
                <div class="stat-value"><?php echo count($subscribers); ?></div>
            </div>
        </div>
        
        <?php if (empty($subscribers)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Newsletter Subscribers Yet</h3>
                <p>Subscribers will appear here when visitors subscribe to the newsletter.</p>
            </div>
        <?php else: ?>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Subscribed Date</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscribers as $subscriber): ?>
                    <tr>
                        <td><?php echo $subscriber['id']; ?></td>
                        <td><a href="mailto:<?php echo $subscriber['email']; ?>"><?php echo $subscriber['email']; ?></a></td>
                        <td><?php echo date('d M Y, H:i', strtotime($subscriber['subscribed_date'])); ?></td>
                        <td><?php echo $subscriber['ip_address'] ?? 'N/A'; ?></td>
                        <td><span style="color: #10b981; font-weight: 600;"><?php echo ucfirst($subscriber['status']); ?></span></td>
                        <td>
                            <button class="btn-small" onclick="unsubscribe(<?php echo $subscriber['id']; ?>)">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php endif; ?>
    </div>
    
    <script>
        function unsubscribe(id) {
            if (!confirm('Are you sure you want to remove this subscriber?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('id', id);
            formData.append('action', 'unsubscribe');
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Subscriber removed successfully');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        }
    </script>
</body>
</html>

