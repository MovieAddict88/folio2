<?php
require_once 'auth_middleware.php';
require_once '../config/config.php';

$error = '';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_experience'])) {
            $stmt = $pdo->prepare('INSERT INTO experience (title, institution, start_year, end_year, description) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$_POST['title'], $_POST['institution'], $_POST['start_year'], $_POST['end_year'], $_POST['description']]);
            header('Location: edit-experience.php?success=Experience added!');
            exit;
        } elseif (isset($_POST['update_experience'])) {
            $stmt = $pdo->prepare('UPDATE experience SET title = ?, institution = ?, start_year = ?, end_year = ?, description = ? WHERE id = ?');
            $stmt->execute([$_POST['title'], $_POST['institution'], $_POST['start_year'], $_POST['end_year'], $_POST['description'], $_POST['id']]);
            header('Location: edit-experience.php?success=Experience updated!');
            exit;
        } elseif (isset($_POST['delete_experience'])) {
            $stmt = $pdo->prepare('DELETE FROM experience WHERE id = ?');
            $stmt->execute([$_POST['id']]);
            header('Location: edit-experience.php?success=Experience deleted!');
            exit;
        }
    }

    $experiences = $pdo->query('SELECT * FROM experience ORDER BY start_year DESC')->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Experience</title>
    <link rel="stylesheet" href="../public/admin-style.css">
</head>
<body>

<?php include 'admin-header.php'; ?>
<div class="main-content">
    <h1>Edit Experience</h1>

    <div class="form-container">
        <h3>Add New Experience</h3>
        <form action="edit-experience.php" method="POST">
            <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
            <div class="form-group"><label>Institution</label><input type="text" name="institution" required></div>
            <div class="form-group"><label>Start Year</label><input type="text" name="start_year" required></div>
            <div class="form-group"><label>End Year</label><input type="text" name="end_year" placeholder="e.g., Present" required></div>
            <div class="form-group"><label>Description</label><textarea name="description"></textarea></div>
            <button type="submit" name="add_experience">Add Experience</button>
        </form>
    </div>

    <div class="item-list">
        <h3>Existing Experience</h3>
        <?php foreach ($experiences as $exp): ?>
            <div class="item">
                <form action="edit-experience.php" method="POST" class="item-form">
                    <input type="hidden" name="id" value="<?php echo $exp['id']; ?>">
                    <input type="text" name="title" value="<?php echo htmlspecialchars($exp['title']); ?>">
                    <input type="text" name="institution" value="<?php echo htmlspecialchars($exp['institution']); ?>">
                    <input type="text" name="start_year" value="<?php echo htmlspecialchars($exp['start_year']); ?>" style="width: 60px;">
                    <input type="text" name="end_year" value="<?php echo htmlspecialchars($exp['end_year']); ?>" style="width: 60px;">
                    <textarea name="description" style="display:none;"><?php echo htmlspecialchars($exp['description']); ?></textarea>
                    <div class="actions">
                        <button type="submit" name="update_experience">Update</button>
                        <button type="submit" name="delete_experience" class="delete" onclick="return confirm('Are you sure?')">Delete</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>