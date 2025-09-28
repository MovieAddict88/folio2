<?php
require_once 'config/config.php';

if (!isset($_GET['token'])) {
    http_response_code(400);
    die('Download token is missing.');
}

$token = $_GET['token'];

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Find the token in the database
    $stmt = $pdo->prepare('SELECT * FROM download_tokens WHERE token = ?');
    $stmt->execute([$token]);
    $token_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$token_data) {
        http_response_code(404);
        die('Invalid or expired token.');
    }

    // 2. Check if the token has expired
    if (new DateTime() > new DateTime($token_data['expires_at'])) {
        http_response_code(401);
        die('Token has expired.');
    }

    // 3. Check if the token has already been used
    if ($token_data['is_used']) {
        http_response_code(401);
        die('This download link has already been used.');
    }

    // 4. Mark the token as used to prevent reuse
    $stmt = $pdo->prepare('UPDATE download_tokens SET is_used = 1 WHERE id = ?');
    $stmt->execute([$token_data['id']]);

    // 5. Get the file path from the main downloads table
    $stmt = $pdo->prepare('SELECT file_path, file_name FROM downloads WHERE id = ?');
    $stmt->execute([$token_data['file_id']]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$file || !file_exists($file['file_path'])) {
        http_response_code(404);
        die('File not found.');
    }

    // 6. Track the download count
    $stmt = $pdo->prepare('UPDATE downloads SET download_count = download_count + 1 WHERE id = ?');
    $stmt->execute([$token_data['file_id']]);

    // 7. Serve the file for download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file['file_name']) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file['file_path']));
    readfile($file['file_path']);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    die('Database error: ' . $e->getMessage());
}
?>