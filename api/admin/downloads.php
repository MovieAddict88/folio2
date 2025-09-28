<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust for production
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../config/config.php';

// Check for admin authentication
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

try {
    switch ($method) {
        case 'POST':
            // Handles CREATE and UPDATE
            $id = $data['id'] ?? null;
            $file_name = $data['file_name'];
            $description = $data['description'];
            $file_path = $data['file_path'];
            $is_password_protected = isset($data['is_password_protected']) ? 1 : 0;
            $password = $data['password'] ?? null;

            if ($id) {
                // Update existing record
                if ($is_password_protected && !empty($password)) {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare('UPDATE downloads SET file_name = ?, description = ?, file_path = ?, is_password_protected = ?, password_hash = ? WHERE id = ?');
                    $stmt->execute([$file_name, $description, $file_path, $is_password_protected, $password_hash, $id]);
                } else {
                    $stmt = $pdo->prepare('UPDATE downloads SET file_name = ?, description = ?, file_path = ?, is_password_protected = ? WHERE id = ?');
                    $stmt->execute([$file_name, $description, $file_path, $is_password_protected, $id]);
                }
                echo json_encode(['success' => true, 'message' => 'Download updated successfully.']);
            } else {
                // Create new record
                $password_hash = $is_password_protected && !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;
                $stmt = $pdo->prepare('INSERT INTO downloads (file_name, description, file_path, is_password_protected, password_hash) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$file_name, $description, $file_path, $is_password_protected, $password_hash]);
                echo json_encode(['success' => true, 'message' => 'Download created successfully.']);
            }
            break;

        case 'DELETE':
            $id = $data['id'] ?? null;
            if (!$id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Download ID is required.']);
                exit;
            }
            $stmt = $pdo->prepare('DELETE FROM downloads WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Download deleted successfully.']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>