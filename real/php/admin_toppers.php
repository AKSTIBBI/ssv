<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';

require_admin_login();

$flash_message = $_SESSION['flash_message'] ?? '';
$flash_type = $_SESSION['flash_type'] ?? '';
unset($_SESSION['flash_message'], $_SESSION['flash_type']);

$toppers = get_json_data(TOPPERS_JSON, []);
if (!is_array($toppers)) {
    $toppers = [];
}

function topper_upload_image($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['', ''];
    }

    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['', 'Image is too large. Maximum allowed is 5MB.'];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_IMAGE_EXTENSIONS, true)) {
        return ['', 'Invalid image type. Allowed: jpg, jpeg, png, gif, webp.'];
    }

    $upload_dir = BASE_PATH . '/images/Toppers/';
    if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true)) {
        return ['', 'Failed to create upload directory.'];
    }

    $filename = 'topper_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target = $upload_dir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        return ['', 'Failed to upload image.'];
    }

    return ['real/images/Toppers/' . $filename, ''];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Security token invalid. Please try again.';
        $_SESSION['flash_type'] = 'error';
        redirect('admin_toppers.php');
    }

    $action = safe_trim($_POST['action'] ?? '');
    $errors = [];

    if ($action === 'add' || $action === 'edit') {
        $year = safe_trim($_POST['year'] ?? '');
        $name = safe_trim($_POST['name'] ?? '');
        $class = safe_trim($_POST['class'] ?? '');
        $rank = safe_trim($_POST['rank'] ?? '');
        $image_path = safe_trim($_POST['image_path'] ?? '');

        if (!preg_match('/^\d{4}$/', $year)) {
            $errors[] = 'Session year must be in YYYY format.';
        }
        if ($name === '') {
            $errors[] = 'Student name is required.';
        }
        if ($class === '') {
            $errors[] = 'Class is required.';
        }
        if ($rank === '') {
            $errors[] = 'Rank/percentage is required.';
        }

        [$uploaded_path, $upload_error] = topper_upload_image($_FILES['image'] ?? null);
        if ($upload_error !== '') {
            $errors[] = $upload_error;
        }
        if ($uploaded_path !== '') {
            $image_path = $uploaded_path;
        }

        if ($action === 'add' && $image_path === '') {
            $errors[] = 'Please provide image path or upload an image.';
        }

        if (empty($errors)) {
            if (!isset($toppers[$year]) || !is_array($toppers[$year])) {
                $toppers[$year] = [];
            }

            if ($action === 'add') {
                $toppers[$year][] = [
                    'name' => $name,
                    'class' => $class,
                    'rank' => $rank,
                    'image' => $image_path
                ];
                $_SESSION['flash_message'] = 'Topper added successfully.';
                $_SESSION['flash_type'] = 'success';
            } else {
                $original_year = safe_trim($_POST['original_year'] ?? '');
                $index = intval($_POST['index'] ?? -1);

                if (!isset($toppers[$original_year][$index])) {
                    $_SESSION['flash_message'] = 'Invalid topper selected for edit.';
                    $_SESSION['flash_type'] = 'error';
                    redirect('admin_toppers.php');
                }

                $existing = $toppers[$original_year][$index];
                $updated = [
                    'name' => $name,
                    'class' => $class,
                    'rank' => $rank,
                    'image' => ($image_path !== '' ? $image_path : ($existing['image'] ?? ''))
                ];

                array_splice($toppers[$original_year], $index, 1);
                if (empty($toppers[$original_year])) {
                    unset($toppers[$original_year]);
                }
                if (!isset($toppers[$year]) || !is_array($toppers[$year])) {
                    $toppers[$year] = [];
                }
                $toppers[$year][] = $updated;

                $_SESSION['flash_message'] = 'Topper updated successfully.';
                $_SESSION['flash_type'] = 'success';
            }

            if (!save_json_file(TOPPERS_JSON, $toppers)) {
                $_SESSION['flash_message'] = 'Failed to save toppers data.';
                $_SESSION['flash_type'] = 'error';
            }
            redirect('admin_toppers.php');
        } else {
            $_SESSION['flash_message'] = implode(' ', $errors);
            $_SESSION['flash_type'] = 'error';
            redirect('admin_toppers.php');
        }
    } elseif ($action === 'delete') {
        $year = safe_trim($_POST['year'] ?? '');
        $index = intval($_POST['index'] ?? -1);
        if (isset($toppers[$year][$index])) {
            array_splice($toppers[$year], $index, 1);
            if (empty($toppers[$year])) {
                unset($toppers[$year]);
            }
            if (save_json_file(TOPPERS_JSON, $toppers)) {
                $_SESSION['flash_message'] = 'Topper deleted successfully.';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to update toppers data.';
                $_SESSION['flash_type'] = 'error';
            }
        } else {
            $_SESSION['flash_message'] = 'Invalid topper selected for delete.';
            $_SESSION['flash_type'] = 'error';
        }
        redirect('admin_toppers.php');
    }
}

$edit_mode = false;
$edit_data = null;
$edit_year = '';
$edit_index = -1;
if (isset($_GET['edit_year'], $_GET['edit_index'])) {
    $candidate_year = safe_trim($_GET['edit_year']);
    $candidate_index = intval($_GET['edit_index']);
    if (isset($toppers[$candidate_year][$candidate_index])) {
        $edit_mode = true;
        $edit_year = $candidate_year;
        $edit_index = $candidate_index;
        $edit_data = $toppers[$candidate_year][$candidate_index];
    }
}

krsort($toppers);
$total_toppers = 0;
foreach ($toppers as $year_list) {
    if (is_array($year_list)) {
        $total_toppers += count($year_list);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toppers Control - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --color-primary:#244855; --color-primary-700:#1e3f49; --color-surface:#fff; --color-bg:#f3f6fa; --color-text:#1f3448; --color-muted:#5f7386; --color-border:#d8e1ea; --radius-sm:8px; --radius-md:14px; --shadow-sm:0 4px 12px rgba(16,32,48,.08);}
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:"Poppins","Segoe UI",Tahoma,sans-serif; background:var(--color-bg); color:var(--color-text); padding:20px 14px; }
        .container { max-width:1320px; margin:0 auto; }
        .header { background:linear-gradient(135deg,var(--color-primary) 0%,var(--color-primary-700) 100%); color:#fff; border-radius:var(--radius-md); box-shadow:var(--shadow-sm); padding:22px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; }
        .header h1 { font-size:clamp(24px,3vw,30px); }
        .btn-logout { padding:10px 14px; background:#c62828; color:#fff; border:1px solid #c62828; border-radius:var(--radius-sm); cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-weight:600; transition:all .2s ease; }
        .btn-logout:hover { background:#a91f1f; border-color:#a91f1f; }
        .admin-nav { display:flex; flex-wrap:wrap; gap:8px; padding:8px; margin-bottom:16px; background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); }
        .admin-nav-link { display:inline-flex; align-items:center; gap:6px; padding:9px 12px; border-radius:var(--radius-sm); text-decoration:none; color:var(--color-primary); font-size:14px; font-weight:500; transition:.2s; }
        .admin-nav-link:hover { background:#eff4f8; }
        .admin-nav-link.active { background:var(--color-primary); color:#fff; }
        .panel { background:#fff; border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); padding:20px; margin-bottom:14px; }
        .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:10px; margin-bottom:14px; }
        .stat { background:#f8fbfd; border:1px solid var(--color-border); border-radius:var(--radius-sm); padding:12px; }
        .stat .label { color:var(--color-muted); font-size:12px; text-transform:uppercase; }
        .stat .value { color:var(--color-primary); font-weight:700; font-size:26px; }
        .message { padding:12px 14px; border-radius:var(--radius-sm); margin-bottom:14px; }
        .message.success { background:#e9f8ef; color:#0b6b3c; border:1px solid #9ad7b4; }
        .message.error { background:#fff6f6; color:#8f1f1f; border:1px solid #f4b6b6; }
        .form-row { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; }
        .form-group { margin-bottom:12px; }
        label { display:block; margin-bottom:6px; color:var(--color-primary); font-weight:600; font-size:14px; }
        input { width:100%; padding:10px; border:1px solid var(--color-border); border-radius:var(--radius-sm); }
        input:focus { outline:none; border-color:var(--color-primary); box-shadow:0 0 0 3px rgba(36,72,85,.12); }
        .help { font-size:12px; color:var(--color-muted); margin-top:4px; display:block; }
        .btn-row { display:flex; gap:10px; flex-wrap:wrap; margin-top:8px; }
        .btn { padding:10px 14px; border:1px solid transparent; border-radius:var(--radius-sm); text-decoration:none; font-weight:600; font-size:14px; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
        .btn-primary { background:var(--color-primary); color:#fff; border-color:var(--color-primary); }
        .btn-primary:hover { background:var(--color-primary-700); }
        .btn-secondary { background:#6c7e90; color:#fff; border-color:#6c7e90; }
        .btn-danger { background:#c62828; color:#fff; border-color:#c62828; }
        .year-block { margin-top:16px; }
        .year-title { font-size:18px; color:var(--color-primary); margin-bottom:10px; }
        .table-wrap { border:1px solid var(--color-border); border-radius:var(--radius-md); overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:10px; border-bottom:1px solid var(--color-border); text-align:left; font-size:14px; }
        th { background:#f6f9fc; color:var(--color-primary); }
        .img-thumb { width:46px; height:46px; object-fit:cover; border-radius:8px; border:1px solid var(--color-border); }
        .actions { display:flex; gap:8px; }
        .empty { color:var(--color-muted); }
        @media (max-width:768px) { .form-row{grid-template-columns:1fr;} .actions{flex-wrap:wrap;} }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-star"></i> Toppers Control</h1>
            <a href="admin_logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <?php include 'admin_nav.php'; ?>

        <div class="panel">
            <div class="stats">
                <div class="stat"><div class="label">Total Toppers</div><div class="value"><?php echo $total_toppers; ?></div></div>
                <div class="stat"><div class="label">Sessions</div><div class="value"><?php echo count($toppers); ?></div></div>
            </div>
            <?php if ($flash_message): ?>
                <div class="message <?php echo safe_output($flash_type ?: 'success'); ?>"><?php echo safe_output($flash_message); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="action" value="<?php echo $edit_mode ? 'edit' : 'add'; ?>">
                <?php if ($edit_mode): ?>
                    <input type="hidden" name="original_year" value="<?php echo safe_output($edit_year); ?>">
                    <input type="hidden" name="index" value="<?php echo safe_output((string)$edit_index); ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label>Session Year *</label>
                        <input type="text" name="year" maxlength="4" value="<?php echo $edit_mode ? safe_output($edit_year) : date('Y'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Student Name *</label>
                        <input type="text" name="name" value="<?php echo $edit_mode ? safe_output($edit_data['name'] ?? '') : ''; ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Class *</label>
                        <input type="text" name="class" value="<?php echo $edit_mode ? safe_output($edit_data['class'] ?? '') : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Rank / Percentage *</label>
                        <input type="text" name="rank" value="<?php echo $edit_mode ? safe_output($edit_data['rank'] ?? '') : ''; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Image Path <?php echo $edit_mode ? '' : '*'; ?></label>
                    <input type="text" name="image_path" placeholder="real/images/Toppers/your-image.jpg" value="<?php echo $edit_mode ? safe_output($edit_data['image'] ?? '') : ''; ?>">
                    <span class="help">You can paste an existing path or upload a new image below.</span>
                </div>
                <div class="form-group">
                    <label>Upload Image (Optional)</label>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">
                </div>
                <div class="btn-row">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo $edit_mode ? 'Update Topper' : 'Add Topper'; ?></button>
                    <?php if ($edit_mode): ?><a href="admin_toppers.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel Edit</a><?php endif; ?>
                </div>
            </form>
        </div>

        <div class="panel">
            <h2 class="year-title"><i class="fas fa-list"></i> Existing Toppers</h2>
            <?php if (empty($toppers)): ?>
                <p class="empty">No toppers added yet.</p>
            <?php else: ?>
                <?php foreach ($toppers as $year => $items): ?>
                    <div class="year-block">
                        <h3 class="year-title"><?php echo safe_output((string)$year); ?></h3>
                        <div class="table-wrap">
                            <table>
                                <thead><tr><th>Image</th><th>Name</th><th>Class</th><th>Rank</th><th>Actions</th></tr></thead>
                                <tbody>
                                    <?php foreach ($items as $idx => $item): ?>
                                        <tr>
                                            <td><?php if (!empty($item['image'])): ?><img src="<?php echo safe_output(BASE_URL . '/' . ltrim(str_replace('real/', '', $item['image']), '/')); ?>" class="img-thumb" alt="topper" onerror="this.style.display='none'"><?php endif; ?></td>
                                            <td><?php echo safe_output($item['name'] ?? ''); ?></td>
                                            <td><?php echo safe_output($item['class'] ?? ''); ?></td>
                                            <td><?php echo safe_output($item['rank'] ?? ''); ?></td>
                                            <td>
                                                <div class="actions">
                                                    <a class="btn btn-secondary" href="admin_toppers.php?edit_year=<?php echo urlencode((string)$year); ?>&edit_index=<?php echo urlencode((string)$idx); ?>"><i class="fas fa-edit"></i> Edit</a>
                                                    <form method="POST" onsubmit="return confirm('Delete this topper?');">
                                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="year" value="<?php echo safe_output((string)$year); ?>">
                                                        <input type="hidden" name="index" value="<?php echo safe_output((string)$idx); ?>">
                                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
