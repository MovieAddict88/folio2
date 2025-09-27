<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo 'Invalid email format.';
        exit;
    }

    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch admin email from the database
        $stmt = $pdo->query('SELECT email FROM about_me LIMIT 1');
        $about = $stmt->fetch(PDO::FETCH_ASSOC);
        $admin_email = $about['email'] ?? 'admin@example.com'; // Fallback email

        // --- Send Email (Auto-Reply and Notification) ---
        $subject_to_admin = "New Contact Form Submission from {$name}";
        $message_to_admin = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";
        $headers_to_admin = "From: webmaster@example.com\r\nReply-To: {$email}";

        // The mail() function is typically disabled in sandboxed environments.
        // In a live server, this would send an email to the administrator.
        // mail($admin_email, $subject_to_admin, $message_to_admin, $headers_to_admin);

        $subject_to_user = "Thank you for contacting us!";
        $message_to_user = "Hi {$name},\n\nThank you for your message. We have received it and will get back to you shortly.\n\nBest regards,\nPortfolio Admin";
        $headers_to_user = "From: {$admin_email}";

        // mail($email, $subject_to_user, $message_to_user, $headers_to_user);

        header('Location: ../index.php?contact_success=1#contact');
        exit;

    } catch (PDOException $e) {
        // In a real app, log this error instead of echoing it
        http_response_code(500);
        echo 'A database error occurred.';
        exit;
    }

} else {
    http_response_code(405); // Method Not Allowed
    echo 'Invalid request method.';
    exit;
}
?>