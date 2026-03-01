<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';
require_once 'sms_config_repository.php';

require_admin_login();
if (Auth::has_timed_out()) {
    redirect('admin_login.php?session=expired');
}

$status = '';
$error = '';
$config = sms_config_get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    $form_action = safe_trim($_POST['form_action'] ?? 'save_settings');
    if (!Auth::verify_csrf($csrf_token)) {
        $error = 'Invalid security token. Please refresh and try again.';
    } else {
        $config['provider_name'] = safe_trim($_POST['provider_name'] ?? 'Priority SMS');
        $config['api_url'] = safe_trim($_POST['api_url'] ?? '');
        $config['api_key'] = safe_trim($_POST['api_key'] ?? '');
        $config['sender_id'] = safe_trim($_POST['sender_id'] ?? '');
        $config['method'] = safe_trim($_POST['method'] ?? 'sms');
        $config['enabled'] = isset($_POST['enabled']);
        $config['use_dummy_api'] = isset($_POST['use_dummy_api']);
        $config['bypass_enabled'] = isset($_POST['bypass_enabled']);
        $config['bypass_code'] = preg_replace('/\D/', '', safe_trim($_POST['bypass_code'] ?? '999999'));
        $config['otp_expiry_seconds'] = (int)($_POST['otp_expiry_seconds'] ?? 300);
        $config['updated_at'] = date('Y-m-d H:i:s');
        $config = sms_config_normalize($config);

        if ($config['enabled'] && (empty($config['api_url']) || empty($config['api_key']) || empty($config['sender_id']))) {
            $error = 'To enable live SMS, API URL, API Key, and Sender ID are required.';
        } else {
            if (!sms_config_save($config)) {
                $error = 'Failed to save SMS settings.';
            } else {
                if ($form_action === 'test_sms') {
                    $test_mobile = preg_replace('/\D/', '', safe_trim($_POST['test_mobile'] ?? ''));
                    if (strlen($test_mobile) !== 10) {
                        $error = 'Enter a valid 10-digit mobile number for test SMS.';
                    } else {
                        $test_otp = str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
                        [$ok, $msg] = sms_send_with_config($config, $test_mobile, $test_otp);
                        if ($ok) {
                            $status = $msg . ' Test OTP: ' . $test_otp;
                        } else {
                            $error = $msg;
                        }
                    }
                } else {
                    $status = 'SMS settings saved successfully.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Settings - SSVET Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --color-primary:#244855; --color-primary-700:#1e3f49; --color-surface:#ffffff; --color-bg:#f3f6fa; --color-text:#1f3448; --color-muted:#5f7386; --color-border:#d8e1ea; --radius-sm:8px; --radius-md:14px; --shadow-sm:0 4px 12px rgba(16,32,48,.08); }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:"Poppins","Segoe UI",Tahoma,Geneva,Verdana,sans-serif; background:var(--color-bg); color:var(--color-text); min-height:100vh; padding:20px 14px; }
        .admin-container { max-width:1320px; margin:0 auto; }
        .admin-header { background:linear-gradient(135deg,var(--color-primary) 0%,var(--color-primary-700) 100%); color:#fff; border-radius:var(--radius-md); box-shadow:var(--shadow-sm); padding:24px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; }
        .admin-header h1 { color:#fff; font-size:clamp(24px,3vw,30px); }
        .btn-logout { padding:10px 14px; background:#c62828; color:#fff; border:1px solid #c62828; border-radius:var(--radius-sm); cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-weight:600; transition:all .2s ease; }
        .btn-logout:hover { background:#a91f1f; border-color:#a91f1f; }
        .admin-nav { display:flex; flex-wrap:wrap; gap:8px; padding:8px; margin-bottom:16px; background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); }
        .admin-nav-link { display:inline-flex; align-items:center; gap:6px; padding:9px 12px; border-radius:var(--radius-sm); text-decoration:none; color:var(--color-primary); font-size:14px; font-weight:500; transition:all .2s ease; }
        .admin-nav-link:hover { background:#eff4f8; color:var(--color-primary-700); }
        .admin-nav-link.active { background:var(--color-primary); color:#fff; }
        .panel { background:#fff; border:1px solid var(--color-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm); padding:18px; }
        .panel h2 { color:var(--color-primary); margin-bottom:12px; font-size:20px; }
        .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:12px; }
        .field { display:flex; flex-direction:column; gap:6px; }
        .field label { font-size:13px; color:var(--color-muted); font-weight:600; }
        .field input, .field select { border:1px solid var(--color-border); border-radius:8px; padding:10px 12px; font-size:14px; }
        .checkbox-row { display:flex; flex-wrap:wrap; gap:16px; margin:12px 0 8px; }
        .checkbox-row label { display:inline-flex; gap:8px; align-items:center; font-size:14px; color:var(--color-text); }
        .actions { margin-top:14px; display:flex; gap:10px; }
        .btn { padding:10px 14px; border-radius:8px; border:1px solid transparent; font-weight:600; cursor:pointer; }
        .btn-primary { background:var(--color-primary); color:#fff; }
        .btn-primary:hover { background:var(--color-primary-700); }
        .msg { margin-bottom:12px; padding:10px 12px; border-radius:8px; font-size:14px; }
        .msg.ok { background:#e6f7ee; color:#17643a; border:1px solid #c2ebd2; }
        .msg.err { background:#ffe9e9; color:#8b1a1a; border:1px solid #f5c1c1; }
        .subpanel { margin-top:16px; border-top:1px dashed var(--color-border); padding-top:14px; }
        .subpanel h3 { color:var(--color-primary); margin-bottom:10px; font-size:16px; }
        @media (max-width: 700px) {
            .admin-header { padding:18px; }
            .admin-nav-link { font-size:12px; padding:8px 10px; }
            .actions { flex-direction:column; }
        }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <h1><i class="fas fa-sms"></i> SMS Settings</h1>
        <a href="admin_logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <?php include 'admin_nav.php'; ?>

    <div class="panel">
        <h2>School Pro OTP Gateway Configuration</h2>
        <?php if (!empty($status)): ?><div class="msg ok"><?php echo safe_output($status); ?></div><?php endif; ?>
        <?php if (!empty($error)): ?><div class="msg err"><?php echo safe_output($error); ?></div><?php endif; ?>

        <form method="POST">
            <?php echo csrf_token_input(); ?>
            <div class="grid">
                <div class="field">
                    <label>Provider Name</label>
                    <input type="text" name="provider_name" value="<?php echo safe_output($config['provider_name']); ?>">
                </div>
                <div class="field">
                    <label>API URL</label>
                    <input type="text" name="api_url" value="<?php echo safe_output($config['api_url']); ?>">
                </div>
                <div class="field">
                    <label>API Key</label>
                    <input type="text" name="api_key" value="<?php echo safe_output($config['api_key']); ?>">
                </div>
                <div class="field">
                    <label>Sender ID</label>
                    <input type="text" name="sender_id" value="<?php echo safe_output($config['sender_id']); ?>">
                </div>
                <div class="field">
                    <label>Method</label>
                    <select name="method">
                        <option value="sms" <?php echo $config['method'] === 'sms' ? 'selected' : ''; ?>>sms</option>
                    </select>
                </div>
                <div class="field">
                    <label>OTP Expiry (seconds)</label>
                    <input type="number" name="otp_expiry_seconds" min="60" max="1800" value="<?php echo (int)$config['otp_expiry_seconds']; ?>">
                </div>
                <div class="field">
                    <label>Bypass OTP Code (testing)</label>
                    <input type="text" name="bypass_code" maxlength="6" value="<?php echo safe_output($config['bypass_code']); ?>">
                </div>
            </div>

            <div class="checkbox-row">
                <label><input type="checkbox" name="enabled" <?php echo !empty($config['enabled']) ? 'checked' : ''; ?>> Enable Live SMS</label>
                <label><input type="checkbox" name="use_dummy_api" <?php echo !empty($config['use_dummy_api']) ? 'checked' : ''; ?>> Use Dummy SMS API</label>
                <label><input type="checkbox" name="bypass_enabled" <?php echo !empty($config['bypass_enabled']) ? 'checked' : ''; ?>> Enable OTP Bypass (Testing)</label>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit" name="form_action" value="save_settings"><i class="fas fa-save"></i> Save Settings</button>
            </div>

            <div class="subpanel">
                <h3><i class="fas fa-paper-plane"></i> Test SMS</h3>
                <div class="grid">
                    <div class="field">
                        <label>Test Mobile Number</label>
                        <input type="text" name="test_mobile" maxlength="10" placeholder="Enter 10-digit mobile">
                    </div>
                </div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit" name="form_action" value="test_sms"><i class="fas fa-vial"></i> Send Test SMS</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
