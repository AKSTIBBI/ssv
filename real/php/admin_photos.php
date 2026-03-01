<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';
require_once 'photos_repository.php';

require_admin_login();

$flash_message = $_SESSION['flash_message'] ?? '';
$flash_type = $_SESSION['flash_type'] ?? '';
unset($_SESSION['flash_message'], $_SESSION['flash_type']);

$photos = photos_get_all();
if (!is_array($photos)) {
    $photos = [];
}

function photo_upload_image($file) {
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

    $upload_dir = BASE_PATH . '/images/Gallery/';
    if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true)) {
        return ['', 'Failed to create upload directory.'];
    }

    $filename = 'photo_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target = $upload_dir . $filename;
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        return ['', 'Failed to upload image.'];
    }

    return ['real/images/Gallery/' . $filename, ''];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Security token invalid. Please try again.';
        $_SESSION['flash_type'] = 'error';
        redirect('admin_photos.php');
    }

    $action = safe_trim($_POST['action'] ?? '');
    $errors = [];

    if ($action === 'add' || $action === 'edit') {
        $title = safe_trim($_POST['title'] ?? '');
        $category = safe_trim($_POST['category'] ?? '');
        $image_path = safe_trim($_POST['image_path'] ?? '');

        if ($title === '') {
            $errors[] = 'Photo title is required.';
        }
        if ($category === '') {
            $errors[] = 'Category is required.';
        }

        [$uploaded_path, $upload_error] = photo_upload_image($_FILES['image'] ?? null);
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
            if ($action === 'add') {
                $new_photo = [
                    'title' => $title,
                    'image_path' => $image_path,
                    'category' => strtolower($category)
                ];
                if (photos_add($new_photo)) {
                    $_SESSION['flash_message'] = 'Photo added successfully.';
                    $_SESSION['flash_type'] = 'success';
                } else {
                    $_SESSION['flash_message'] = 'Failed to save photos data.';
                    $_SESSION['flash_type'] = 'error';
                }
            } else {
                $photo_id = safe_trim($_POST['photo_id'] ?? '');
                if ($photo_id === '') {
                    $_SESSION['flash_message'] = 'Invalid photo selected for edit.';
                    $_SESSION['flash_type'] = 'error';
                    redirect('admin_photos.php');
                }

                $payload = [
                    'title' => $title,
                    'category' => strtolower($category),
                    'image_path' => $image_path
                ];
                if (photos_update($photo_id, $payload)) {
                    $_SESSION['flash_message'] = 'Photo updated successfully.';
                    $_SESSION['flash_type'] = 'success';
                } else {
                    $_SESSION['flash_message'] = 'Failed to save photos data.';
                    $_SESSION['flash_type'] = 'error';
                }
            }
            redirect('admin_photos.php');
        } else {
            $_SESSION['flash_message'] = implode(' ', $errors);
            $_SESSION['flash_type'] = 'error';
            redirect('admin_photos.php');
        }
    } elseif ($action === 'delete') {
        $photo_id = safe_trim($_POST['photo_id'] ?? '');
        if ($photo_id !== '' && photos_delete($photo_id)) {
            $_SESSION['flash_message'] = 'Photo deleted successfully.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Failed to update photos data.';
            $_SESSION['flash_type'] = 'error';
        }
        redirect('admin_photos.php');
    }
}

$photos = photos_get_all();
$edit_mode = false;
$edit_photo_id = '';
$edit_data = null;
if (isset($_GET['edit'])) {
    $candidate_id = safe_trim($_GET['edit']);
    foreach ($photos as $photo_item) {
        if (($photo_item['photo_id'] ?? '') === $candidate_id) {
            $edit_mode = true;
            $edit_photo_id = $candidate_id;
            $edit_data = $photo_item;
            break;
        }
    }
}

$total_photos = count($photos);
$categories = [];
foreach ($photos as $p) {
    $cat = strtolower($p['category'] ?? 'uncategorized');
    $categories[$cat] = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photos Control - Admin</title>
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
        .grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(210px,1fr)); gap:12px; margin-top:10px; }
        .card { border:1px solid var(--color-border); border-radius:var(--radius-md); overflow:hidden; background:#fff; }
        .card img { width:100%; height:150px; object-fit:cover; background:#f1f5f9; }
        .card-body { padding:10px; }
        .card-title { font-weight:600; color:var(--color-text); margin-bottom:6px; }
        .chip { display:inline-block; font-size:12px; background:#e8eff5; color:var(--color-primary); padding:3px 8px; border-radius:999px; margin-bottom:8px; }
        .actions { display:flex; gap:8px; flex-wrap:wrap; }
        .empty { color:var(--color-muted); }
        @media (max-width:768px) { .form-row{grid-template-columns:1fr;} }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-images"></i> Photos Control</h1>
            <a href="admin_logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <?php include 'admin_nav.php'; ?>

        <div class="panel">
            <div class="stats">
                <div class="stat"><div class="label">Total Photos</div><div class="value"><?php echo $total_photos; ?></div></div>
                <div class="stat"><div class="label">Categories</div><div class="value"><?php echo count($categories); ?></div></div>
            </div>
            <?php if ($flash_message): ?>
                <div class="message <?php echo safe_output($flash_type ?: 'success'); ?>"><?php echo safe_output($flash_message); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="action" value="<?php echo $edit_mode ? 'edit' : 'add'; ?>">
                <?php if ($edit_mode): ?><input type="hidden" name="photo_id" value="<?php echo safe_output($edit_photo_id); ?>"><?php endif; ?>
                <div class="form-row">
                    <div class="form-group">
                        <label>Photo Title *</label>
                        <input type="text" name="title" required value="<?php echo $edit_mode ? safe_output($edit_data['title'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Category *</label>
                        <input type="text" name="category" placeholder="events, campus, activities" required value="<?php echo $edit_mode ? safe_output($edit_data['category'] ?? '') : ''; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Image Path <?php echo $edit_mode ? '' : '*'; ?></label>
                    <input type="text" name="image_path" placeholder="real/images/Gallery/photo.jpg" value="<?php echo $edit_mode ? safe_output($edit_data['image_path'] ?? '') : ''; ?>">
                    <span class="help">Paste an existing path or upload a file below.</span>
                </div>
                <div class="form-group">
                    <label>Upload Image (Optional)</label>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">
                </div>
                <div class="btn-row">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo $edit_mode ? 'Update Photo' : 'Add Photo'; ?></button>
                    <?php if ($edit_mode): ?><a href="admin_photos.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel Edit</a><?php endif; ?>
                </div>
            </form>
        </div>

        <div class="panel">
            <h2 style="color:#244855;margin-bottom:8px;"><i class="fas fa-list"></i> Existing Photos</h2>
            <?php if (empty($photos)): ?>
                <p class="empty">No photos added yet.</p>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($photos as $photo): ?>
                        <div class="card">
                            <img src="<?php echo safe_output(BASE_URL . '/' . ltrim(str_replace('real/', '', $photo['image_path'] ?? ''), '/')); ?>" alt="<?php echo safe_output($photo['title'] ?? 'photo'); ?>" onerror="this.style.display='none'">
                            <div class="card-body">
                                <div class="card-title"><?php echo safe_output($photo['title'] ?? 'Untitled'); ?></div>
                                <span class="chip"><?php echo safe_output($photo['category'] ?? 'uncategorized'); ?></span>
                                <div class="actions">
                                    <a class="btn btn-secondary" href="admin_photos.php?edit=<?php echo urlencode((string)($photo['photo_id'] ?? '')); ?>"><i class="fas fa-edit"></i> Edit</a>
                                    <form method="POST" onsubmit="return confirm('Delete this photo?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="photo_id" value="<?php echo safe_output((string)($photo['photo_id'] ?? '')); ?>">
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
