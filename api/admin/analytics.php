<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust for production

require_once '../../config/config.php';

// Check for admin authentication
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // 1. Fetch real download counts from the database
    $stmt_downloads = $pdo->query('SELECT file_name, download_count FROM downloads ORDER BY download_count DESC');
    $downloads_data = $stmt_downloads->fetchAll(PDO::FETCH_ASSOC);

    // 2. Placeholder for Visitor Data
    // NOTE: The visitor data below is for demonstration purposes only.
    // In a production environment, you would replace this with a real analytics tracking system,
    // such as logging page views to a database table or integrating with a service like Google Analytics.
    $visitors_data = [
        ['date' => '2023-10-01', 'visits' => 150],
        ['date' => '2023-10-02', 'visits' => 180],
        ['date' => '2023-10-03', 'visits' => 220],
        ['date' => '2023-10-04', 'visits' => 190],
        ['date' => '2023-10-05', 'visits' => 250],
        ['date' => '2023-10-06', 'visits' => 280],
        ['date' => '2023-10-07', 'visits' => 300],
    ];

    // 3. Combine and return the data
    echo json_encode([
        'success' => true,
        'downloads' => $downloads_data,
        'visitors' => $visitors_data
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>