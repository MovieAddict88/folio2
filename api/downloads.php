<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Select file metadata but exclude the direct URL for security
    // We assume the table has an 'id' and 'is_password_protected' column
    $stmt = $pdo->query('SELECT id as file_id, file_name, description, is_password_protected FROM downloads');
    $downloads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($downloads);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>