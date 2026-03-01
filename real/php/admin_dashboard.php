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

$admin_email = get_admin_email();

// Load statistics
$faculties = get_json_data(FACULTY_JSON);
$notices = get_json_data(NOTICES_JSON);
$toppers = get_json_data(TOPPERS_JSON);
$photos = get_json_data(PHOTOS_JSON);

$faculty_count = count($faculties);
$notice_count = count($notices);
$topper_count = count($toppers);
$photo_count = count($photos);
// load career application count
$career_apps = get_json_data(JSON_PATH . 'career_applications.json');
$career_count = count($career_apps);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SSVET</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --color-primary:#244855; --color-primary-700:#1e3f49; --color-surface:#ffffff; --color-bg:#f3f6fa; --color-text:#1f3448; --color-muted:#5f7386; --color-border:#d8e1ea; --radius-sm:8px; --radius-md:14px; --shadow-sm:0 4px 12px rgba(16,32,48,.08); }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:"Poppins","Segoe UI",Tahoma,Geneva,Verdana,sans-serif; background:var(--color-bg); color:var(--color-text); min-height:100vh; padding:20px 14px; }
        .admin-container { max-width:1320px; margin:0 auto; }
        .admin-header { background:linear-gradient(135deg,var(--color-primary) 0%,var(--color-primary-700) 100%); color:#fff; border-radius:var(--radius-md); box-shadow:var(--shadow-sm); padding:24px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; }
        .admin-header h1 { color:#fff; font-size:clamp(24px,3vw,30px); }
        .admin-header-actions { display:flex; gap:10px; }
        .btn-logout { padding:10px 14px; background:#c62828; color:#fff; border:1px solid #c62828; border-radius:var(--radius-sm); cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-weight:600; transition:all .2s ease; }
        .btn-logout:hover { background:#a91f1f; border-color:#a91f1f; }

        .admin-nav { display:flex; flex-wrap:wrap; gap:8px; padding:8px; margin-bottom:16px; background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); }
        .admin-nav-link { display:inline-flex; align-items:center; gap:6px; padding:9px 12px; border-radius:var(--radius-sm); text-decoration:none; color:var(--color-primary); font-size:14px; font-weight:500; transition:all .2s ease; }
        .admin-nav-link:hover { background:#eff4f8; color:var(--color-primary-700); }
        .admin-nav-link.active { background:var(--color-primary); color:#fff; }

        .dashboard-cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:14px; margin-bottom:20px; }
        .dashboard-card { background:#fff; border:1px solid var(--color-border); border-radius:var(--radius-md); padding:20px; box-shadow:var(--shadow-sm); transition:transform .2s ease, box-shadow .2s ease; text-align:center; }
        .dashboard-card:hover { transform:translateY(-2px); box-shadow:0 10px 20px rgba(16,32,48,.12); }
        .dashboard-card h3 { color:var(--color-muted); font-size:12px; text-transform:uppercase; margin-bottom:8px; letter-spacing:.4px; }
        .dashboard-card .count { font-size:42px; color:var(--color-primary); font-weight:700; margin-bottom:8px; line-height:1.1; }
        .dashboard-card .icon { font-size:32px; margin-bottom:8px; }
        .dashboard-card.faculty .icon { color:#2f80c9; }
        .dashboard-card.notices .icon { color:#ef8f17; }
        .dashboard-card.toppers .icon { color:#198f63; }
        .dashboard-card.photos .icon { color:#7c52b8; }
        .dashboard-card.admissions .icon { color:#0f8b9c; }
        .dashboard-card.career-apps .icon { color:#5f7386; }
        .dashboard-card a { display:inline-flex; align-items:center; justify-content:center; margin-top:8px; color:var(--color-primary); text-decoration:none; font-weight:600; font-size:14px; }
        .dashboard-card a:hover { text-decoration:underline; }

        .info-section { background:#fff; border:1px solid var(--color-border); border-radius:var(--radius-md); padding:20px; box-shadow:var(--shadow-sm); }
        .info-section h3 { color:var(--color-primary); margin-bottom:14px; font-size:18px; display:inline-flex; align-items:center; gap:8px; }
        .info-item { display:flex; justify-content:space-between; align-items:flex-start; gap:10px; padding:10px 0; border-bottom:1px solid var(--color-border); }
        .info-item:last-child { border-bottom:none; }
        .info-label { color:var(--color-muted); font-weight:600; }
        .info-value { color:var(--color-text); text-align:right; }

        @media (max-width: 700px) {
            .admin-header { padding:18px; }
            .admin-nav-link { font-size:12px; padding:8px 10px; }
            .dashboard-card .count { font-size:34px; }
            .info-item { flex-direction:column; }
            .info-value { text-align:left; }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <div>
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
            </div>
            <div class="admin-header-actions">
                <a href="admin_logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <?php include 'admin_nav.php'; ?>

        <div class="dashboard-cards">
            <div class="dashboard-card faculty">
                <div class="icon"><i class="fas fa-users"></i></div>
                <h3>Total Faculty</h3>
                <div class="count"><?php echo $faculty_count; ?></div>
                <a href="admin_faculties.php">Manage Faculties</a>
            </div>

            <div class="dashboard-card notices">
                <div class="icon"><i class="fas fa-bullhorn"></i></div>
                <h3>Total Notices</h3>
                <div class="count"><?php echo $notice_count; ?></div>
                <a href="admin_notices.php">Manage Notices</a>
            </div>

            <div class="dashboard-card toppers">
                <div class="icon"><i class="fas fa-star"></i></div>
                <h3>Total Toppers</h3>
                <div class="count"><?php echo $topper_count; ?></div>
                <a href="admin_toppers.php">Manage Toppers</a>
            </div>

            <div class="dashboard-card photos">
                <div class="icon"><i class="fas fa-images"></i></div>
                <h3>Total Photos</h3>
                <div class="count"><?php echo $photo_count; ?></div>
                <a href="admin_photos.php">Manage Photos</a>
            </div>

            <div class="dashboard-card admissions">
                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                <h3>Total Enquiries</h3>
                <div class="count"><?php
                    $enqs = get_json_data(JSON_PATH . 'admission_enquiries.json');
                    echo is_array($enqs) ? count($enqs) : 0;
?>
                </div>
                <a href="admin_admissions.php">Manage Enquiries</a>
            </div>

            <div class="dashboard-card career-apps">
                <div class="icon"><i class="fas fa-briefcase"></i></div>
                <h3>Career Applications</h3>
                <div class="count"><?php echo isset($career_count) ? $career_count : 0; ?></div>
                <a href="admin_careers.php">View Applications</a>
            </div>
        </div>

        <div class="info-section">
            <h3><i class="fas fa-info-circle"></i> System Information</h3>
            <div class="info-item">
                <span class="info-label">Admin Email:</span>
                <span class="info-value"><?php echo safe_output($admin_email); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Last Login:</span>
                <span class="info-value"><?php echo isset($_SESSION['last_login']) ? format_date($_SESSION['last_login']) : 'Just now'; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">PHP Version:</span>
                <span class="info-value"><?php echo phpversion(); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Application:</span>
                <span class="info-value"><?php echo APP_NAME; ?></span>
            </div>
        </div>
    </div>
</body>
</html>


