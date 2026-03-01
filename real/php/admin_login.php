<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';

$is_embed = isset($_GET['embed']) && $_GET['embed'] === '1';
$open_dashboard_new_tab = false;

// Redirect if already logged in
if (Auth::is_logged_in()) {
    if ($is_embed) {
        $open_dashboard_new_tab = true;
    } else {
        redirect('admin_dashboard.php');
    }
}

$error_message = '';
$success_message = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $email = safe_trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!Auth::verify_csrf($csrf_token)) {
        $error_message = "Invalid security token. Please try again.";
        log_message("CSRF token mismatch", 'warning');
    }
    // Validate inputs
    elseif (empty($email) || empty($password)) {
        $error_message = "Please enter both email and password.";
    }
    // Validate email format
    elseif (!is_valid_email($email)) {
        $error_message = "Please enter a valid email address.";
    }
    // Attempt login
    elseif (Auth::login($email, $password)) {
        if ($is_embed) {
            $success_message = "Login successful. Opening dashboard in a new tab...";
            $open_dashboard_new_tab = true;
        } else {
            $success_message = "Login successful. Redirecting to dashboard...";
            header("Refresh: 1; url=admin_dashboard.php");
        }
    } else {
        $error_message = "Invalid email or password.";
    }
}

// Generate CSRF token for the form
$csrf_token = Auth::csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SSVET</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #244855;
            background:
                radial-gradient(circle at 12% 20%, rgba(255, 229, 105, 0.12), transparent 36%),
                radial-gradient(circle at 90% 10%, rgba(183, 4, 4, 0.14), transparent 28%),
                linear-gradient(140deg, #112832 0%, #1c4250 40%, #244855 100%);
            position: relative;
            overflow: hidden;
        }

        body::before,
        body::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        body::before {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 26px 26px;
            opacity: 0.35;
        }

        body::after {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.35));
            opacity: 0.35;
        }

        .login-container {
            position: relative;
            z-index: 1;
            background: #faf6e3;
            border-radius: 14px;
            border-top: 5px solid #ffe569;
            border-bottom: 5px solid #b70404;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
            width: 100%;
            max-width: 430px;
            padding: 36px 30px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #244855;
            font-size: 30px;
            margin-bottom: 8px;
            letter-spacing: 0.4px;
        }

        .login-header p {
            color: #475a61;
            font-size: 13px;
            font-weight: 600;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease-in;
        }

        .alert-error {
            background: #ffe6e6;
            color: #8b1a1a;
            border: 1px solid #f2b5b5;
        }

        .alert-success {
            background: #e5f8eb;
            color: #19623a;
            border: 1px solid #b7e8c8;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            color: #244855;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            padding: 12px 15px;
            border: 1px solid #c5d0d4;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .form-group input:focus {
            outline: none;
            border-color: #244855;
            box-shadow: 0 0 0 3px rgba(36, 72, 85, 0.18);
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #244855;
            font-size: 13px;
            margin-top: 2px;
        }

        .btn-login {
            padding: 12px;
            background: linear-gradient(135deg, #244855 0%, #1e3f4b 100%);
            color: #ffe569;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 10px 18px rgba(36, 72, 85, 0.32);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            filter: brightness(1.02);
            box-shadow: 0 14px 24px rgba(36, 72, 85, 0.42);
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #66777c;
            font-size: 12px;
            line-height: 1.55;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Admin Login</h1>
            <p>SHRI SIDDHI VINAYAK EDUCATIONAL TRUST TIBBI</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo safe_output($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo safe_output($success_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="login-form" action="<?php echo $is_embed ? 'admin_login.php?embed=1' : 'admin_login.php'; ?>">
            <?php echo csrf_token_input(); ?>

            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" id="email" name="email" placeholder="admin@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="margin: 0;">Remember me</label>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> LOGIN
            </button>
        </form>

        <div class="login-footer">
            <p>For admin access, use your credentials provided by the administrator.</p>
            <p style="margin-top: 10px; font-size: 11px; color: #999;">
                Demo: admin@example.com / admin123
            </p>
        </div>
    </div>

    <?php if ($open_dashboard_new_tab): ?>
    <script>
        (function () {
            const dashboardUrl = new URL('admin_dashboard.php', window.location.href).href;
            const popup = window.open(dashboardUrl, '_blank', 'noopener');

            // If popup is blocked, fallback to opening dashboard in current/top tab.
            if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                if (window.top && window.top !== window) {
                    window.top.location.href = dashboardUrl;
                } else {
                    window.location.href = dashboardUrl;
                }
            } else if (typeof popup.focus === 'function') {
                popup.focus();
            }
        })();
    </script>
    <?php endif; ?>
</body>
</html>


