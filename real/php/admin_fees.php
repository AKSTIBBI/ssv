<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';

require_admin_login();

$action = $_POST['action'] ?? '';
$flash_message = '';
$flash_type = '';
$errors = [];

// Load fees data
$fees = get_json_data(FEES_JSON, []);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        $flash_message = "Security token invalid. Please try again.";
        $flash_type = 'error';
    } elseif ($action === 'update') {
        $fee_data = [
            'class' => safe_trim($_POST['class'] ?? ''),
            'monthly_fee' => safe_trim($_POST['monthly_fee'] ?? ''),
            'annual_fee' => safe_trim($_POST['annual_fee'] ?? ''),
            'special_charges' => safe_trim($_POST['special_charges'] ?? ''),
            'discount' => safe_trim($_POST['discount'] ?? ''),
            'description' => safe_trim($_POST['description'] ?? '')
        ];

        // Basic validation
        if (empty($fee_data['class'])) {
            $errors[] = 'Class is required';
        }
        if (!is_numeric($fee_data['monthly_fee'])) {
            $errors[] = 'Monthly fee must be a number';
        }
        if (!is_numeric($fee_data['annual_fee'])) {
            $errors[] = 'Annual fee must be a number';
        }

        if (empty($errors)) {
            // Update or add fee
            $fee_index = -1;
            foreach ($fees as $i => $f) {
                if ($f['class'] === $fee_data['class']) {
                    $fee_index = $i;
                    break;
                }
            }

            if ($fee_index >= 0) {
                // Update existing
                $fees[$fee_index] = $fee_data;
                $message = "Fee structure updated successfully!";
            } else {
                // Add new
                $fees[] = $fee_data;
                $message = "Fee structure added successfully!";
            }

            if (save_json_file(FEES_JSON, $fees)) {
                log_message("Fee structure updated: {$fee_data['class']}");
                $flash_message = $message;
                $flash_type = 'success';
                header("Refresh: 2; url=" . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $flash_message = "Failed to save fee structure. Please try again.";
                $flash_type = 'error';
            }
        }
    } elseif ($action === 'delete') {
        $delete_class = safe_trim($_POST['delete_class'] ?? '');
        $fee_index = -1;

        foreach ($fees as $i => $f) {
            if ($f['class'] === $delete_class) {
                $fee_index = $i;
                break;
            }
        }

        if ($fee_index >= 0) {
            array_splice($fees, $fee_index, 1);
            if (save_json_file(FEES_JSON, $fees)) {
                log_message("Fee structure deleted: {$delete_class}");
                $flash_message = "Fee structure deleted successfully!";
                $flash_type = 'success';
                header("Refresh: 2; url=" . $_SERVER['PHP_SELF']);
                exit;
            }
        }
    }
}

// Get fee for editing
$edit_fee = null;
$edit_class = $_GET['edit'] ?? '';
if ($edit_class) {
    foreach ($fees as $f) {
        if ($f['class'] === $edit_class) {
            $edit_fee = $f;
            break;
        }
    }
}

$is_editing = $edit_fee !== null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Management - Admin Panel</title>
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
            --radius-sm: 8px;
            --radius-md: 14px;
            --shadow-sm: 0 4px 12px rgba(16, 32, 48, 0.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "Poppins", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            min-height: 100vh;
            padding: 20px 14px;
        }

        .container {
            max-width: 1320px;
            margin: 0 auto;
        }

        .header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-700) 100%);
            color: #fff;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            padding: 24px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
        }

        .header h1 {
            font-size: clamp(24px, 3vw, 30px);
            margin: 0 0 14px;
        }

        .admin-nav { display:flex; flex-wrap:wrap; gap:8px; padding:8px; margin-bottom:16px; background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); }
        .admin-nav-link { display:inline-flex; align-items:center; gap:6px; padding:9px 12px; border-radius:var(--radius-sm); text-decoration:none; color:var(--color-primary); font-size:14px; font-weight:500; transition:.2s; }
        .admin-nav-link:hover { background:#eff4f8; }
        .admin-nav-link.active { background:var(--color-primary); color:#fff; }
        .btn-logout { padding:10px 14px; background:#c62828; color:#fff; border:1px solid #c62828; border-radius:var(--radius-sm); cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-weight:600; transition:all .2s ease; }
        .btn-logout:hover { background:#a91f1f; border-color:#a91f1f; }

        .content {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            padding: 24px;
        }

        .flash-message {
            padding: 12px 14px;
            border-radius: var(--radius-sm);
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            animation: slideIn 0.25s ease;
        }

        .flash-message.success { background: #e9f8ef; color: #0b6b3c; border: 1px solid #9ad7b4; }
        .flash-message.error { background: #fdecec; color: #8f1f1f; border: 1px solid #f4b6b6; }

        .flash-close-btn {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
        }

        .error-list {
            background: #fff6f6;
            color: #8f1f1f;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 16px;
            border: 1px solid #f4b6b6;
        }

        .form-section {
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            background: #fbfdff;
            padding: 18px;
            margin-bottom: 22px;
        }

        .form-section h2 {
            color: var(--color-primary);
            margin-bottom: 14px;
            font-size: 22px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--color-primary);
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: inherit;
            color: var(--color-text);
            background: #fff;
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(36, 72, 85, 0.12);
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 14px;
            border: 1px solid transparent;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary { background: var(--color-primary); color: #fff; border-color: var(--color-primary); }
        .btn-primary:hover { background: var(--color-primary-700); border-color: var(--color-primary-700); }
        .btn-secondary { background: #6c7e90; color: #fff; border-color: #6c7e90; }
        .btn-secondary:hover { background: #5b6c7d; border-color: #5b6c7d; }

        .table-responsive {
            margin-top: 12px;
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

        th { color: var(--color-primary); font-weight: 600; white-space: nowrap; }

        tbody tr:hover { background: #f9fbfd; }

        .empty-state {
            text-align: center;
            padding: 38px 16px;
            color: var(--color-muted);
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .btn-edit, .btn-delete {
            padding: 8px 10px;
            border-radius: 7px;
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-edit { background: #1f7a44; }
        .btn-edit:hover { background: #166437; }
        .btn-delete { background: #c62828; }
        .btn-delete:hover { background: #a91f1f; }

        .inline-form {
            display: inline;
        }

        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 900px) {
            .form-row { grid-template-columns: 1fr; }
        }

        @media (max-width: 600px) {
            .content { padding: 16px; }
            .admin-nav-link { font-size: 12px; padding: 8px 10px; }
            th, td { padding: 10px; font-size: 13px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💰 Fee Management</h1>
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

            <!-- Fee Form -->
            <div class="form-section">
                <h2><?php echo $is_editing ? '✏️ Edit Fee Structure' : '➕ Add Fee Structure'; ?></h2>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="action" value="update">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="class">Class/Grade *</label>
                            <input type="text" id="class" name="class" required 
                                value="<?php echo $is_editing ? safe_output($edit_fee['class']) : ''; ?>"
                                placeholder="e.g., Class I, Class XII"
                                <?php echo $is_editing ? 'readonly' : ''; ?>>
                        </div>
                        <div class="form-group">
                            <label for="monthly_fee">Monthly Fee (₹) *</label>
                            <input type="number" id="monthly_fee" name="monthly_fee" required step="0.01"
                                value="<?php echo $is_editing ? safe_output($edit_fee['monthly_fee']) : ''; ?>"
                                placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="annual_fee">Annual Fee (₹) *</label>
                            <input type="number" id="annual_fee" name="annual_fee" required step="0.01"
                                value="<?php echo $is_editing ? safe_output($edit_fee['annual_fee']) : ''; ?>"
                                placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label for="discount">Discount (%) </label>
                            <input type="number" id="discount" name="discount" step="0.01"
                                value="<?php echo $is_editing ? safe_output($edit_fee['discount']) : '0'; ?>"
                                placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="special_charges">Special Charges/Additional Fees</label>
                        <input type="text" id="special_charges" name="special_charges"
                            value="<?php echo $is_editing ? safe_output($edit_fee['special_charges']) : ''; ?>"
                            placeholder="e.g., Lab fee, Sports fee, Transport">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" 
                            placeholder="Enter additional details about fee structure"><?php echo $is_editing ? safe_output($edit_fee['description']) : ''; ?></textarea>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 
                            <?php echo $is_editing ? 'Update Fee' : 'Add Fee'; ?>
                        </button>
                        <?php if ($is_editing): ?>
                            <a href="admin_fees.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Fee Structure List -->
            <h2>📋 Current Fee Structures</h2>
            <div class="table-responsive">
                <?php if (empty($fees)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No fee structures added yet. Add one to get started!</p>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Class/Grade</th>
                                <th>Monthly Fee</th>
                                <th>Annual Fee</th>
                                <th>Special Charges</th>
                                <th>Discount (%)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fees as $fee): ?>
                                <tr>
                                    <td><strong><?php echo safe_output($fee['class']); ?></strong></td>
                                    <td>₹ <?php echo number_format((float)$fee['monthly_fee'], 2); ?></td>
                                    <td>₹ <?php echo number_format((float)$fee['annual_fee'], 2); ?></td>
                                    <td><?php echo safe_output($fee['special_charges'] ?? '-'); ?></td>
                                    <td><?php echo safe_output($fee['discount'] ?? '0'); ?>%</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="?edit=<?php echo urlencode($fee['class']); ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form method="POST" class="inline-form" onsubmit="return confirm('Delete this fee structure?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="delete_class" value="<?php echo safe_output($fee['class']); ?>">
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
</body>
</html>

