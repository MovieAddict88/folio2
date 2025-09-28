<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/config.php'; // Now includes reCAPTCHA keys

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // 1. Validate form fields
    if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
        exit();
    }

    // 2. Validate reCAPTCHA
    if (empty($data['g-recaptcha-response'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'CAPTCHA verification failed. Please try again.']);
        exit();
    }

    $recaptcha_secret = RECAPTCHA_SECRET_KEY; // Use the constant from config.php
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_response = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $data['g-recaptcha-response']);
    $recaptcha_data = json_decode($recaptcha_response);

    if (!$recaptcha_data || !$recaptcha_data->success) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'CAPTCHA verification failed. Are you a robot?']);
        exit();
    }

    // 3. Process the form (simulation)
    // In a real application, you would send an email, save to a database, etc.
    // For example: mail($admin_email, "New Contact Form Submission", "From: {$data['name']}...");

    echo json_encode(['success' => true, 'message' => 'Your message has been sent successfully!']);

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
}
?>