<?php
/**
 * Common navigation bar for admin pages.
 * Include this file where a navigation menu is required.
 */
?>
<div class="admin-nav">
    <a href="admin_dashboard.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_dashboard.php' ? ' active' : ''; ?>">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="admin_notices.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_notices.php' ? ' active' : ''; ?>">
        <i class="fas fa-bullhorn"></i> Notices
    </a>
    <a href="admin_toppers.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_toppers.php' ? ' active' : ''; ?>">
        <i class="fas fa-star"></i> Toppers
    </a>
    <a href="admin_photos.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_photos.php' ? ' active' : ''; ?>">
        <i class="fas fa-images"></i> Photos
    </a>
    <a href="admin_videos.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_videos.php' ? ' active' : ''; ?>">
        <i class="fas fa-video"></i> Videos
    </a>
    <a href="admin_faculties.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_faculties.php' ? ' active' : ''; ?>">
        <i class="fas fa-users"></i> Faculties
    </a>
    <a href="admin_fees.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_fees.php' ? ' active' : ''; ?>">
        <i class="fas fa-money-bill-wave"></i> Fees
    </a>
    <a href="admin_financials.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_financials.php' ? ' active' : ''; ?>">
        <i class="fas fa-file-invoice-dollar"></i> Financials
    </a>
    <a href="admin_uploads.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_uploads.php' ? ' active' : ''; ?>">
        <i class="fas fa-upload"></i> Uploads
    </a>
    <a href="admin_admissions.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_admissions.php' ? ' active' : ''; ?>">
        <i class="fas fa-user-graduate"></i> Admissions
    </a>
    <a href="admin_careers.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_careers.php' ? ' active' : ''; ?>">
        <i class="fas fa-briefcase"></i> Career Applications
    </a>
    <a href="admin_sms_settings.php" class="admin-nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'admin_sms_settings.php' ? ' active' : ''; ?>">
        <i class="fas fa-sms"></i> SMS Settings
    </a>
</div>
