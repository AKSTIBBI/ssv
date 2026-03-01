<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';

// Logout the admin
admin_logout();

// Redirect back to website and open admin login in middle section
redirect('/Project_SSV_Website/index.html?open=adminLogin&logout=success');
?>
