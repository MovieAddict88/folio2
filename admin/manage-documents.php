<?php
require_once 'auth_middleware.php';
require_once '../config/config.php';

$error = '';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['upload_document'])) {
            if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
                $target_dir = "../documents/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

                $file_name = basename($_FILES["document"]["name"]);
                $target_file = $target_dir . $file_name;

                if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
                    $stmt = $pdo->prepare('INSERT INTO documents (file_name, file_path) VALUES (?, ?)');
                    $stmt->execute([$file_name, 'documents/' . $file_name]);
                    header('Location: manage-documents.php?success=Document uploaded!');
                    exit;
                } else {
                    $error = "Sorry, there was an error uploading your file.";
                }
            } else {
                $error = "No file was uploaded or an error occurred.";
            }
        } elseif (isset($_POST['delete_document'])) {
            $id = $_POST['id'];
            $stmt = $pdo->prepare('SELECT file_path FROM documents WHERE id = ?');
            $stmt->execute([$id]);
            $doc = $stmt->fetch();

            if ($doc && file_exists('../' . $doc['file_path'])) {
                unlink('../' . $doc['file_path']);
            }

            $pdo->prepare('DELETE FROM passwords WHERE document_id = ?')->execute([$id]);
            $pdo->prepare('DELETE FROM documents WHERE id = ?')->execute([$id]);

            header('Location: manage-documents.php?success=Document deleted!');
            exit;
        }
    }

    $documents = $pdo->query('SELECT * FROM documents ORDER BY uploaded_at DESC')->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Documents</title>
    <link rel="stylesheet" href="../public/admin-style.css">
</head>
<body>

<?php include 'admin-header.php'; ?>
<div class="main-content">
    <h1>Manage Documents</h1>

    <div class="form-container">
        <h3>Upload New Document</h3>
        <form action="manage-documents.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="document" required>
            <button type="submit" name="upload_document">Upload</button>
        </form>
    </div>

    <div class="item-list">
        <h3>Uploaded Documents</h3>
        <?php foreach ($documents as $doc): ?>
            <div class="item">
                <span><?php echo htmlspecialchars($doc['file_name']); ?> (Downloads: <?php echo $doc['download_count']; ?>)</span>
                <div>
                    <a href="../<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank">View</a>
                    <form action="manage-documents.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
                        <button type="submit" name="delete_document" class="delete" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>