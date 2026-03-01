<?php
/**
 * Career Application Form Handler
 * Processes job application submissions and stores data in JSON
 */

require_once 'config.php';

header('Content-Type: application/json');

// only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(false, 'Invalid request method');
}

// required fields list
$required = [
    'full_name' => 'Full Name',
    'email' => 'Email Address',
    'phone' => 'Phone Number',
    'position' => 'Position',
    'qualification' => 'Highest Qualification',
    'experience' => 'Years of Experience',
    'cover_letter' => 'Cover Letter'
];

$form = [];
$errors = [];

// sanitize and validate required
foreach ($required as $field => $label) {
    if (empty($_POST[$field])) {
        $errors[] = "$label is required";
    } else {
        $form[$field] = htmlspecialchars(trim($_POST[$field]), ENT_QUOTES, 'UTF-8');
    }
}

// additional conditional field
if (!empty($_POST['position']) && $_POST['position'] === 'Other') {
    if (empty($_POST['other_position'])) {
        $errors[] = 'Please specify the other position';
    } else {
        $form['other_position'] = htmlspecialchars(trim($_POST['other_position']), ENT_QUOTES, 'UTF-8');
    }
} else {
    $form['other_position'] = '';
}

// optional reference field
$form['references'] = !empty($_POST['references']) ? htmlspecialchars(trim($_POST['references']), ENT_QUOTES, 'UTF-8') : '';

// validate email
if (!empty($form['email']) && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

// validate phone
if (!empty($form['phone']) && !preg_match('/^[0-9]{10}$/', preg_replace('/[^0-9]/', '', $form['phone']))) {
    $errors[] = 'Please enter a valid 10-digit phone number';
}

// check terms acceptance
if (empty($_POST['terms'])) {
    $errors[] = 'Please accept the terms and conditions';
}

// resume link/file logic
$resumeLink = !empty($_POST['resume']) ? trim($_POST['resume']) : '';
$resumeFilePath = '';

if ($resumeLink) {
    // simple pattern for drive/dropbox
    if (!preg_match('/^(https?:\/\/)?(www\.)?(drive\.google\.com|dropbox\.com).*/', $resumeLink)) {
        $errors[] = 'Please provide a valid Google Drive or Dropbox link for resume';
    } else {
        $form['resume_link'] = htmlspecialchars($resumeLink, ENT_QUOTES, 'UTF-8');
    }
} else {
    $form['resume_link'] = '';
}

// handle uploaded file if any
if (!empty($_FILES['resume_file']) && $_FILES['resume_file']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['resume_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Error uploading resume file';
    } else {
        $allowedExt = ['pdf','doc','docx'];
        $allowedTypes = ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt) || !in_array($file['type'], $allowedTypes)) {
            $errors[] = 'Resume file must be PDF or Word document';
        }
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            $errors[] = 'Resume file must be less than ' . (MAX_UPLOAD_SIZE/1024/1024) . 'MB';
        }
        if (empty($errors)) {
            $uploadDir = UPLOAD_PATH . 'careers/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $target = $uploadDir . time() . '_' . basename($file['name']);
            if (move_uploaded_file($file['tmp_name'], $target)) {
                // store relative path from project root to display later
                $resumeFilePath = str_replace(BASE_PATH . DIRECTORY_SEPARATOR, '', $target);                // convert windows backslashes to forward slashes for URLs
                $resumeFilePath = str_replace('\\', '/', $resumeFilePath);                $form['resume_file'] = $resumeFilePath;
            } else {
                $errors[] = 'Failed to save uploaded resume file';
            }
        }
    }
} else {
    $form['resume_file'] = '';
}

// require at least one of link or file
if (empty($form['resume_link']) && empty($form['resume_file'])) {
    $errors[] = 'Please provide a resume link or upload a file';
}

if (!empty($errors)) {
    send_response(false, 'Please correct the following errors: ' . implode(', ', $errors));
}

// prepare JSON storage
$applicationsJson = JSON_PATH . 'career_applications.json';
$applications = [];
if (file_exists($applicationsJson)) {
    $content = file_get_contents($applicationsJson);
    if (!empty($content)) {
        $applications = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $applications = [];
        }
    }
}

$application = array_merge(
    ['id' => count($applications) + 1],
    $form,
    [
        'submission_date' => date('Y-m-d H:i:s'),
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'status' => 'new',
        'notes' => ''
    ]
);

$applications[] = $application;

if (file_put_contents($applicationsJson, json_encode($applications, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
    send_response(true, 'Thank you for your application! We will review it and get back to you soon.', [
        'application_id' => $application['id']
    ]);
} else {
    send_response(false, 'Failed to submit application. Please try again later.');
}
