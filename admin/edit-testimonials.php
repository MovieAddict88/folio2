<?php
require_once 'auth_middleware.php';
require_once '../config/config.php';

$error = '';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_testimonial'])) {
            $stmt = $pdo->prepare('INSERT INTO testimonials (quote, author) VALUES (?, ?)');
            $stmt->execute([$_POST['quote'], $_POST['author']]);
            header('Location: edit-testimonials.php?success=Testimonial added!');
            exit;
        } elseif (isset($_POST['delete_testimonial'])) {
            $stmt = $pdo->prepare('DELETE FROM testimonials WHERE id = ?');
            $stmt->execute([$_POST['id']]);
            header('Location: edit-testimonials.php?success=Testimonial deleted!');
            exit;
        }
    }

    $testimonials = $pdo->query('SELECT * FROM testimonials ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Testimonials</title>
    <link rel="stylesheet" href="../public/admin-style.css">
</head>
<body>

<?php include 'admin-header.php'; ?>
<div class="main-content">
    <h1>Manage Testimonials</h1>

    <div class="form-container">
        <h3>Add New Testimonial</h3>
        <form action="edit-testimonials.php" method="POST">
            <div class="form-group"><label>Quote</label><textarea name="quote" required></textarea></div>
            <div class="form-group"><label>Author</label><input type="text" name="author" required></div>
            <button type="submit" name="add_testimonial">Add Testimonial</button>
        </form>
    </div>

    <div class="item-list">
        <h3>Existing Testimonials</h3>
        <?php foreach ($testimonials as $testimonial): ?>
            <div class="item">
                <div>
                    <p>"<?php echo htmlspecialchars($testimonial['quote']); ?>"</p>
                    <strong>- <?php echo htmlspecialchars($testimonial['author']); ?></strong>
                </div>
                <form action="edit-testimonials.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
                    <button type="submit" name="delete_testimonial" class="delete" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>