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
            // This handles both CREATE and UPDATE
            $id = $data['id'] ?? null;
            $title = $data['title'];
            $description = $data['description'];
            $media_url = $data['media_url'];
            $external_links = $data['external_links'];
            $category_tags = $data['category_tags'];

            if ($id) {
                // Update
                $stmt = $pdo->prepare('UPDATE projects SET title = ?, description = ?, media_url = ?, external_links = ?, category_tags = ? WHERE id = ?');
                $stmt->execute([$title, $description, $media_url, $external_links, $category_tags, $id]);
                echo json_encode(['success' => true, 'message' => 'Project updated successfully.']);
            } else {
                // Create
                $stmt = $pdo->prepare('INSERT INTO projects (title, description, media_url, external_links, category_tags) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$title, $description, $media_url, $external_links, $category_tags]);
                echo json_encode(['success' => true, 'message' => 'Project created successfully.']);
            }
            break;

        case 'DELETE':
            $id = $data['id'] ?? null;
            if (!$id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Project ID is required.']);
                exit;
            }
            $stmt = $pdo->prepare('DELETE FROM projects WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Project deleted successfully.']);
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