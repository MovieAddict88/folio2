<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['password_code']) || !isset($_POST['document_id'])) {
    header('Location: index.php'); // Or a generic error page
    exit;
}

$code = $_POST['password_code'];
$document_id = $_POST['document_id'];
$redirect_url = 'download.php?file_id=' . urlencode($document_id);

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('
        SELECT d.file_path, p.expiration_date, p.is_active
        FROM passwords p
        JOIN documents d ON p.document_id = d.id
        WHERE p.code = ? AND p.document_id = ?
    ');
    $stmt->execute([$code, $document_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        header('Location: ' . $redirect_url . '&error=' . urlencode('Invalid password code for this document.'));
        exit;
    }

    if (!$result['is_active']) {
        header('Location: ' . $redirect_url . '&error=' . urlencode('This download code has been deactivated.'));
        exit;
    }

    if ($result['expiration_date'] && new DateTime() > new DateTime($result['expiration_date'])) {
        header('Location: ' . $redirect_url . '&error=' . urlencode('This download code has expired.'));
        exit;
    }

    $file_path = '../' . $result['file_path'];

    if (file_exists($file_path)) {
        // Increment download count
        $stmt = $pdo->prepare('UPDATE documents SET download_count = download_count + 1 WHERE id = ?');
        $stmt->execute([$document_id]);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        header('Location: ' . $redirect_url . '&error=' . urlencode('File not found on server.'));
        exit;
    }

} catch (PDOException $e) {
    header('Location: ' . $redirect_url . '&error=' . urlencode('Database error.'));
    exit;
}
?>