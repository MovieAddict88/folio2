<?php
require_once 'auth_middleware.php';
require_once '../config/config.php';

$error = '';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_skill'])) {
            $stmt = $pdo->prepare('INSERT INTO skills (skill_name, level, category) VALUES (?, ?, ?)');
            $stmt->execute([$_POST['skill_name'], $_POST['level'], $_POST['category']]);
            header('Location: edit-skills.php?success=Skill added!');
            exit;
        } elseif (isset($_POST['update_skill'])) {
            $stmt = $pdo->prepare('UPDATE skills SET skill_name = ?, level = ?, category = ? WHERE id = ?');
            $stmt->execute([$_POST['skill_name'], $_POST['level'], $_POST['category'], $_POST['id']]);
            header('Location: edit-skills.php?success=Skill updated!');
            exit;
        } elseif (isset($_POST['delete_skill'])) {
            $stmt = $pdo->prepare('DELETE FROM skills WHERE id = ?');
            $stmt->execute([$_POST['id']]);
            header('Location: edit-skills.php?success=Skill deleted!');
            exit;
        }
    }

    $skills = $pdo->query('SELECT * FROM skills ORDER BY category, skill_name')->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Skills</title>
    <link rel="stylesheet" href="../public/admin-style.css">
</head>
<body>

<?php include 'admin-header.php'; ?>
<div class="main-content">
    <h1>Edit Skills</h1>

    <div class="form-container">
        <h3>Add New Skill</h3>
        <form action="edit-skills.php" method="POST">
            <div class="form-group"><label>Skill Name</label><input type="text" name="skill_name" required></div>
            <div class="form-group"><label>Level (1-5)</label><input type="number" name="level" min="1" max="5" required></div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="Soft">Soft</option>
                    <option value="Hard">Hard</option>
                </select>
            </div>
            <button type="submit" name="add_skill">Add Skill</button>
        </form>
    </div>

    <div class="item-list">
        <h3>Existing Skills</h3>
        <?php foreach ($skills as $skill): ?>
            <div class="item">
                <form action="edit-skills.php" method="POST" class="item-form">
                    <input type="hidden" name="id" value="<?php echo $skill['id']; ?>">
                    <input type="text" name="skill_name" value="<?php echo htmlspecialchars($skill['skill_name']); ?>" required>
                    <input type="number" name="level" min="1" max="5" value="<?php echo htmlspecialchars($skill['level']); ?>" required>
                    <select name="category" required>
                        <option value="Soft" <?php if ($skill['category'] == 'Soft') echo 'selected'; ?>>Soft</option>
                        <option value="Hard" <?php if ($skill['category'] == 'Hard') echo 'selected'; ?>>Hard</option>
                    </select>
                    <div class="actions">
                        <button type="submit" name="update_skill">Update</button>
                        <button type="submit" name="delete_skill" class="delete" onclick="return confirm('Are you sure?')">Delete</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>