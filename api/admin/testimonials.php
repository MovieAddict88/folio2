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
            $author_name = $data['author_name'];
            $author_position = $data['author_position'];
            $testimonial_text = $data['testimonial_text'];
            $author_image_url = $data['author_image_url'];
            $video_url = $data['video_url'];

            if ($id) {
                $stmt = $pdo->prepare('UPDATE testimonials SET author_name = ?, author_position = ?, testimonial_text = ?, author_image_url = ?, video_url = ? WHERE id = ?');
                $stmt->execute([$author_name, $author_position, $testimonial_text, $author_image_url, $video_url, $id]);
                echo json_encode(['success' => true, 'message' => 'Testimonial updated successfully.']);
            } else {
                $stmt = $pdo->prepare('INSERT INTO testimonials (author_name, author_position, testimonial_text, author_image_url, video_url) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$author_name, $author_position, $testimonial_text, $author_image_url, $video_url]);
                echo json_encode(['success' => true, 'message' => 'Testimonial created successfully.']);
            }
            break;

        case 'DELETE':
            $id = $data['id'] ?? null;
            if (!$id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Testimonial ID is required.']);
                exit;
            }
            $stmt = $pdo->prepare('DELETE FROM testimonials WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Testimonial deleted successfully.']);
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