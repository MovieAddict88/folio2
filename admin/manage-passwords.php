<?php
require_once 'auth_middleware.php';
require_once '../config/config.php';

$error = '';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['generate_password'])) {
            $code = substr(bin2hex(random_bytes(8)), 0, 12);
            $stmt = $pdo->prepare('INSERT INTO passwords (code, document_id, expiration_date) VALUES (?, ?, ?)');
            $stmt->execute([$code, $_POST['document_id'], $_POST['expiration_date']]);
            header("Location: manage-passwords.php?success=New password generated! Code: " . urlencode($code));
            exit;
        } elseif (isset($_POST['toggle_active'])) {
            $stmt = $pdo->prepare('UPDATE passwords SET is_active = NOT is_active WHERE id = ?');
            $stmt->execute([$_POST['id']]);
            header('Location: manage-passwords.php?success=Password status updated.');
            exit;
        } elseif (isset($_POST['delete_password'])) {
            $stmt = $pdo->prepare('DELETE FROM passwords WHERE id = ?');
            $stmt->execute([$_POST['id']]);
            header('Location: manage-passwords.php?success=Password deleted.');
            exit;
        }
    }

    $documents = $pdo->query('SELECT id, file_name FROM documents')->fetchAll(PDO::FETCH_ASSOC);
    $passwords = $pdo->query('
        SELECT p.id, p.code, p.expiration_date, p.is_active, d.file_name
        FROM passwords p JOIN documents d ON p.document_id = d.id
        ORDER BY p.id DESC
    ')->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Download Passwords</title>
    <link rel="stylesheet" href="../public/admin-style.css">
</head>
<body>

<?php include 'admin-header.php'; ?>
<div class="main-content">
    <h1>Manage Download Passwords</h1>

    <div class="form-container">
        <h3>Generate New Password</h3>
        <form action="manage-passwords.php" method="POST">
            <div class="form-group">
                <label>Select Document</label>
                <select name="document_id" required>
                    <option value="">-- Choose a Document --</option>
                    <?php foreach ($documents as $doc): ?>
                        <option value="<?php echo $doc['id']; ?>"><?php echo htmlspecialchars($doc['file_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Expiration Date</label>
                <input type="datetime-local" name="expiration_date" required>
            </div>
            <button type="submit" name="generate_password">Generate Password</button>
        </form>
    </div>

    <div class="item-list">
        <h3>Generated Passwords</h3>
        <?php foreach ($passwords as $pass): ?>
            <div class="item">
                <span>
                    <strong><?php echo htmlspecialchars($pass['code']); ?></strong> for
                    <em><?php echo htmlspecialchars($pass['file_name']); ?></em>
                </span>
                <div class="actions">
                    <form action="manage-passwords.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $pass['id']; ?>">
                        <button type="submit" name="toggle_active" class="<?php echo $pass['is_active'] ? 'active' : 'inactive'; ?>">
                            <?php echo $pass['is_active'] ? 'Deactivate' : 'Activate'; ?>
                        </button>
                        <button type="submit" name="delete_password" class="delete" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>