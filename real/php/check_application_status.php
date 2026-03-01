<?php
/**
 * Check Application Status Handler
 * Allows students to check their admission application status using mobile/email
 */

require_once 'config.php';
require_once 'helpers.php';

header('Content-Type: application/json');

// Handle status lookup
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_type = trim($_POST['search_type'] ?? '');
    $search_value = trim($_POST['search_value'] ?? '');
    
    $errors = [];
    
    // Validate inputs
    if (!$search_type || !in_array($search_type, ['mobile', 'email'])) {
        $errors[] = 'Invalid search type';
    }
    
    if (!$search_value) {
        $errors[] = 'Please enter ' . ($search_type === 'mobile' ? 'mobile number' : 'email address');
    }
    
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $errors[0]
        ]);
        exit;
    }
    
    // Load enquiries
    $enquiries_file = JSON_PATH . 'admission_enquiries.json';
    $enquiries = get_json_data($enquiries_file, []);
    
    // Search for matching record
    $found_enquiry = null;
    foreach ($enquiries as $enquiry) {
        if ($search_type === 'mobile') {
            $mobile_sanitized = preg_replace('/[^0-9]/', '', $search_value);
            $enquiry_mobile = preg_replace('/[^0-9]/', '', $enquiry['contact_number'] ?? '');
            if ($mobile_sanitized === $enquiry_mobile) {
                $found_enquiry = $enquiry;
                break;
            }
        } elseif ($search_type === 'email') {
            if (strtolower($search_value) === strtolower($enquiry['email'] ?? '')) {
                $found_enquiry = $enquiry;
                break;
            }
        }
    }
    
    if (!$found_enquiry) {
        echo json_encode([
            'success' => false,
            'message' => 'No application found with the provided ' . ($search_type === 'mobile' ? 'mobile number' : 'email address') . '. Please check and try again.'
        ]);
        exit;
    }
    
    // Return application details
    echo json_encode([
        'success' => true,
        'message' => 'Application found!',
        'data' => [
            'enquiry_id' => $found_enquiry['id'],
            'student_name' => $found_enquiry['student_name'] ?? 'N/A',
            'applying_class' => $found_enquiry['applying_class'] ?? 'N/A',
            'parent_name' => $found_enquiry['parent_name'] ?? 'N/A',
            'contact_number' => $found_enquiry['contact_number'] ?? 'N/A',
            'email' => $found_enquiry['email'] ?? 'N/A',
            'submission_date' => $found_enquiry['submission_date'] ?? 'N/A',
            'status' => ucfirst($found_enquiry['status'] ?? 'new'),
            'notes' => $found_enquiry['notes'] ?? 'No remarks yet',
            'status_color' => [
                'new' => '#fbbf24',
                'contacted' => '#60a5fa',
                'pending' => '#f97316',
                'completed' => '#10b981'
            ][strtolower($found_enquiry['status'] ?? 'new')] ?? '#9ca3af'
        ]
    ]);
    exit;
}

// If no action, return error
http_response_code(400);
echo json_encode([
    'success' => false,
    'message' => 'Invalid request'
]);
?>
