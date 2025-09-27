<?php
require_once 'config/config.php';

$file_id = $_GET['file_id'] ?? null;
$document = null;
$error = '';

if ($file_id) {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('SELECT file_name FROM documents WHERE id = ?');
        $stmt->execute([$file_id]);
        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$document) {
            $error = "The requested document does not exist.";
        }

    } catch (PDOException $e) {
        $error = "Database connection error.";
    }
} else {
    $error = "No document specified.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Document</title>
    <link rel="stylesheet" href="public/new-style.css">
    <style>
        /* Add some basic styles for the download page to make it look clean */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f2f5;
        }
        .download-box {
            background: var(--primary-bg, #fff);
            color: var(--primary-text, #000);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; }
        input { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; }
        button { width: 100%; padding: 0.75rem; background-color: #1C2B4A; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        .message { padding: 1rem; margin-bottom: 1rem; border-radius: 4px; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="download-box">
        <?php if ($document): ?>
            <h1>Secure Download</h1>
            <p>Please enter the password to download "<?php echo htmlspecialchars($document['file_name']); ?>".</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="message error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <form action="verify-download.php" method="POST">
                <input type="hidden" name="document_id" value="<?php echo htmlspecialchars($file_id); ?>">
                <div class="form-group">
                    <label for="password_code">Password Code</label>
                    <input type="text" id="password_code" name="password_code" required>
                </div>
                <button type="submit">Download File</button>
            </form>
        <?php else: ?>
            <h1>Error</h1>
            <p><?php echo htmlspecialchars($error ?: 'An unknown error occurred.'); ?></p>
            <a href="index.php">Go back to portfolio</a>
        <?php endif; ?>
    </div>
</body>
</html>