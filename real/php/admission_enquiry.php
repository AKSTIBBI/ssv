<?php
/**
 * Admission Enquiry Form Handler
 * Processes admission enquiry form submissions and stores data in JSON
 */

require_once 'config.php';

header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(false, 'Invalid request method');
}

// Validate required fields
$required_fields = [
    'student_name' => 'Student Name',
    'date_of_birth' => 'Date of Birth',
    'gender' => 'Gender',
    'applying_class' => 'Applying Class',
    'parent_name' => 'Parent/Guardian Name',
    'relationship' => 'Relationship',
    'contact_number' => 'Contact Number',
    'email' => 'Email Address',
    'address' => 'Address'
];

$form_data = [];
$errors = [];

// Sanitize and validate input
foreach ($required_fields as $field => $label) {
    if (empty($_POST[$field])) {
        $errors[] = $label . ' is required';
    } else {
        $form_data[$field] = htmlspecialchars(trim($_POST[$field]), ENT_QUOTES, 'UTF-8');
    }
}

// Validate email format
if (!empty($form_data['email']) && !filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

// Validate contact number (basic validation)
if (!empty($form_data['contact_number']) && !preg_match('/^[0-9]{10}$/', preg_replace('/[^0-9]/', '', $form_data['contact_number']))) {
    $errors[] = 'Please enter a valid 10-digit contact number';
}

// Validate date of birth
if (!empty($form_data['date_of_birth'])) {
    $dob = strtotime($form_data['date_of_birth']);
    $today = strtotime(date('Y-m-d'));
    if ($dob === false || $dob > $today) {
        $errors[] = 'Please enter a valid date of birth';
    }
}

// Return validation errors if any
if (!empty($errors)) {
    send_response(false, 'Please correct the following errors: ' . implode(', ', $errors));
}

// Add optional fields
$optional_fields = [
    'previous_school' => 'Previous School',
    'additional_info' => 'Additional Information'
];

foreach ($optional_fields as $field => $label) {
    if (!empty($_POST[$field])) {
        $form_data[$field] = htmlspecialchars(trim($_POST[$field]), ENT_QUOTES, 'UTF-8');
    } else {
        $form_data[$field] = '';
    }
}

// Check terms acceptance
if (empty($_POST['terms'])) {
    send_response(false, 'Please accept the terms and conditions');
}

// Define admission enquiries JSON file
$enquiries_json = JSON_PATH . 'admission_enquiries.json';

// Load existing enquiries
$enquiries = [];
if (file_exists($enquiries_json)) {
    $content = file_get_contents($enquiries_json);
    if (!empty($content)) {
        $enquiries = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $enquiries = [];
        }
    }
}

// Create enquiry entry
$enquiry = array_merge(
    ['id' => count($enquiries) + 1],
    $form_data,
    [
        'submission_date' => date('Y-m-d H:i:s'),
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'status' => 'new',
        'notes' => ''
    ]
);

$enquiries[] = $enquiry;

// Save to JSON file
if (file_put_contents($enquiries_json, json_encode($enquiries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
    send_response(true, 'Thank you for your enquiry! Our admissions team will contact you shortly at the provided contact number.', [
        'enquiry_id' => $enquiry['id'],
        'student_name' => $enquiry['student_name'],
        'submission_date' => $enquiry['submission_date']
    ]);
} else {
    send_response(false, 'Failed to submit enquiry. Please try again later.');
}
?>
