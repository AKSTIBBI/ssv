<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';
require_once 'videos_repository.php';

require_admin_login();

$flash_message = $_SESSION['flash_message'] ?? '';
$flash_type = $_SESSION['flash_type'] ?? '';
unset($_SESSION['flash_message'], $_SESSION['flash_type']);

$videos = videos_get_all();
if (!is_array($videos)) {
    $videos = [];
}

function video_upload_image($file) {
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

    $filename = 'video_thumb_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
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
        redirect('admin_videos.php');
    }

    $action = safe_trim($_POST['action'] ?? '');
    $errors = [];

    if ($action === 'add' || $action === 'edit') {
        $title = safe_trim($_POST['title'] ?? '');
        $category = safe_trim($_POST['category'] ?? '');
        $video_path = safe_trim($_POST['video_path'] ?? '');
        $image_path = safe_trim($_POST['image_path'] ?? '');
        $is_youtube = !empty($_POST['youtube']);

        if ($title === '') $errors[] = 'Video title is required.';
        if ($category === '') $errors[] = 'Category is required.';
        if ($video_path === '') $errors[] = 'Video URL/path is required.';

        [$uploaded_path, $upload_error] = video_upload_image($_FILES['image'] ?? null);
        if ($upload_error !== '') $errors[] = $upload_error;
        if ($uploaded_path !== '') $image_path = $uploaded_path;

        if ($action === 'add' && $image_path === '') {
            $errors[] = 'Please provide thumbnail image path or upload an image.';
        }

        if (empty($errors)) {
            $payload = [
                'title' => $title,
                'category' => strtolower($category),
                'video_path' => $video_path,
                'image_path' => $image_path,
                'youtube' => $is_youtube
            ];

            if ($action === 'add') {
                if (videos_add($payload)) {
                    $_SESSION['flash_message'] = 'Video added successfully.';
                    $_SESSION['flash_type'] = 'success';
                } else {
                    $_SESSION['flash_message'] = 'Failed to save videos data.';
                    $_SESSION['flash_type'] = 'error';
                }
            } else {
                $video_id = safe_trim($_POST['video_id'] ?? '');
                if ($video_id === '' || !videos_update($video_id, $payload)) {
                    $_SESSION['flash_message'] = 'Failed to update video.';
                    $_SESSION['flash_type'] = 'error';
                    redirect('admin_videos.php');
                }
                $_SESSION['flash_message'] = 'Video updated successfully.';
                $_SESSION['flash_type'] = 'success';
            }
            redirect('admin_videos.php');
        } else {
            $_SESSION['flash_message'] = implode(' ', $errors);
            $_SESSION['flash_type'] = 'error';
            redirect('admin_videos.php');
        }
    } elseif ($action === 'delete') {
        $video_id = safe_trim($_POST['video_id'] ?? '');
        if ($video_id !== '' && videos_delete($video_id)) {
            $_SESSION['flash_message'] = 'Video deleted successfully.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Invalid video selected for delete.';
            $_SESSION['flash_type'] = 'error';
        }
        redirect('admin_videos.php');
    }
}

$videos = videos_get_all();
$edit_mode = false;
$edit_video_id = '';
$edit_data = null;
if (isset($_GET['edit'])) {
    $candidate_id = safe_trim($_GET['edit']);
    foreach ($videos as $video_item) {
        if (($video_item['video_id'] ?? '') === $candidate_id) {
            $edit_mode = true;
            $edit_video_id = $candidate_id;
            $edit_data = $video_item;
            break;
        }
    }
}

$total_videos = count($videos);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos Control - Admin</title>
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
        .panel { background:#fff; border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); padding:20px; margin-bottom:14px; }
        .message { padding:12px 14px; border-radius:var(--radius-sm); margin-bottom:14px; }
        .message.success { background:#e9f8ef; color:#0b6b3c; border:1px solid #9ad7b4; }
        .message.error { background:#fff6f6; color:#8f1f1f; border:1px solid #f4b6b6; }
        .form-row { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; }
        .form-group { margin-bottom:12px; }
        label { display:block; margin-bottom:6px; color:var(--color-primary); font-weight:600; font-size:14px; }
        input { width:100%; padding:10px; border:1px solid var(--color-border); border-radius:var(--radius-sm); }
        input:focus { outline:none; border-color:var(--color-primary); box-shadow:0 0 0 3px rgba(36,72,85,.12); }
        .btn-row { display:flex; gap:10px; flex-wrap:wrap; margin-top:8px; }
        .btn { padding:10px 14px; border:1px solid transparent; border-radius:var(--radius-sm); text-decoration:none; font-weight:600; font-size:14px; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
        .btn-primary { background:var(--color-primary); color:#fff; border-color:var(--color-primary); }
        .btn-primary:hover { background:var(--color-primary-700); }
        .btn-secondary { background:#6c7e90; color:#fff; border-color:#6c7e90; }
        .btn-danger { background:#c62828; color:#fff; border-color:#c62828; }
        .table-wrap { border:1px solid var(--color-border); border-radius:var(--radius-md); overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:10px; border-bottom:1px solid var(--color-border); text-align:left; font-size:14px; }
        th { background:#f6f9fc; color:var(--color-primary); }
        .img-thumb { width:52px; height:52px; object-fit:cover; border-radius:8px; border:1px solid var(--color-border); }
        .actions { display:flex; gap:8px; flex-wrap:wrap; }
        @media (max-width:768px) { .form-row{grid-template-columns:1fr;} }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-video"></i> Videos Control</h1>
            <a href="admin_logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <?php include 'admin_nav.php'; ?>

        <div class="panel">
            <div style="margin-bottom:10px;color:#244855;font-weight:700;">Total Videos: <?php echo $total_videos; ?></div>
            <?php if ($flash_message): ?>
                <div class="message <?php echo safe_output($flash_type ?: 'success'); ?>"><?php echo safe_output($flash_message); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="action" value="<?php echo $edit_mode ? 'edit' : 'add'; ?>">
                <?php if ($edit_mode): ?><input type="hidden" name="video_id" value="<?php echo safe_output($edit_video_id); ?>"><?php endif; ?>
                <div class="form-row">
                    <div class="form-group">
                        <label>Video Title *</label>
                        <input type="text" name="title" required value="<?php echo $edit_mode ? safe_output($edit_data['title'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Category *</label>
                        <input type="text" name="category" placeholder="online, events, activities" required value="<?php echo $edit_mode ? safe_output($edit_data['category'] ?? '') : ''; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Video URL/Path *</label>
                        <input type="text" name="video_path" required placeholder="https://www.youtube.com/embed/..." value="<?php echo $edit_mode ? safe_output($edit_data['video_path'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Thumbnail Path <?php echo $edit_mode ? '' : '*'; ?></label>
                        <input type="text" name="image_path" placeholder="real/images/Gallery/thumb.jpg" value="<?php echo $edit_mode ? safe_output($edit_data['image_path'] ?? '') : ''; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Upload Thumbnail (Optional)</label>
                        <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">
                    </div>
                    <div class="form-group" style="display:flex;align-items:center;gap:8px;margin-top:26px;">
                        <input type="checkbox" name="youtube" id="youtube" style="width:auto;" <?php echo ($edit_mode && !empty($edit_data['youtube'])) ? 'checked' : ''; ?>>
                        <label for="youtube" style="margin:0;">This is YouTube video</label>
                    </div>
                </div>
                <div class="btn-row">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo $edit_mode ? 'Update Video' : 'Add Video'; ?></button>
                    <?php if ($edit_mode): ?><a href="admin_videos.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel Edit</a><?php endif; ?>
                </div>
            </form>
        </div>

        <div class="panel">
            <h2 style="color:#244855;margin-bottom:8px;"><i class="fas fa-list"></i> Existing Videos</h2>
            <?php if (empty($videos)): ?>
                <p style="color:#5f7386;">No videos added yet.</p>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Thumb</th><th>Title</th><th>Category</th><th>Type</th><th>Actions</th></tr></thead>
                        <tbody>
                        <?php foreach ($videos as $video): ?>
                            <tr>
                                <td><img src="<?php echo safe_output($video['image_path'] ?? ''); ?>" class="img-thumb" alt="thumb" onerror="this.style.display='none'"></td>
                                <td><?php echo safe_output($video['title'] ?? ''); ?></td>
                                <td><?php echo safe_output($video['category'] ?? ''); ?></td>
                                <td><?php echo !empty($video['youtube']) ? 'YouTube' : 'Direct'; ?></td>
                                <td>
                                    <div class="actions">
                                        <a class="btn btn-secondary" href="<?php echo safe_output($video['video_path'] ?? '#'); ?>" target="_blank"><i class="fas fa-external-link-alt"></i> Open</a>
                                        <a class="btn btn-secondary" href="admin_videos.php?edit=<?php echo urlencode((string)($video['video_id'] ?? '')); ?>"><i class="fas fa-edit"></i> Edit</a>
                                        <form method="POST" onsubmit="return confirm('Delete this video?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="video_id" value="<?php echo safe_output((string)($video['video_id'] ?? '')); ?>">
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
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
</body>
</html>

