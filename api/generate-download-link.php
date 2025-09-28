<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$file_id = $data['file_id'] ?? null;
$password = $data['password'] ?? null;

if (!$file_id) {
    http_response_code(400);
    echo json_encode(['error' => 'File ID is required.']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('SELECT file_path, password_hash, is_password_protected FROM downloads WHERE id = ?');
    $stmt->execute([$file_id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$file) {
        http_response_code(404);
        echo json_encode(['error' => 'File not found.']);
        exit;
    }

    // Verify password if the file is protected
    if ($file['is_password_protected']) {
        if (empty($password) || !password_verify($password, $file['password_hash'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid password.']);
            exit;
        }
    }

    // Generate a secure, unique token
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', time() + 300); // Token expires in 5 minutes

    // Store the token in a new 'download_tokens' table
    // This table should have columns: token, file_id, expires_at, is_used
    $stmt = $pdo->prepare('INSERT INTO download_tokens (token, file_id, expires_at) VALUES (?, ?, ?)');
    $stmt->execute([$token, $file_id, $expires_at]);

    // Return the token to the client
    echo json_encode(['success' => true, 'token' => $token]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>