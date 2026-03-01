<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';

// Logout the admin
admin_logout();

// Redirect to login
redirect('admin_login.php?logout=success');
?>
