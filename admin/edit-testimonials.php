<?php
require_once 'auth_middleware.php';
require_once '../config/config.php';

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle form submission for adding a new testimonial
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_testimonial'])) {
    $quote = $_POST['quote'];
    $author = $_POST['author'];

    $stmt = $pdo->prepare('INSERT INTO testimonials (quote, author) VALUES (?, ?)');
    $stmt->execute([$quote, $author]);
    header('Location: edit-testimonials.php?success=Testimonial added successfully');
    exit;
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM testimonials WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: edit-testimonials.php?success=Testimonial deleted successfully');
    exit;
}

// Fetch all testimonials
$testimonials = $pdo->query('SELECT * FROM testimonials')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Testimonials</title>
    <link rel="stylesheet" href="../public/admin-style.css"> <!-- Assuming a shared admin style -->
</head>
<body>
    <?php include 'admin-header.php'; // A shared header for admin pages ?>

    <div class="main-content">
        <h1>Manage Testimonials</h1>

        <!-- Add Testimonial Form -->
        <form action="edit-testimonials.php" method="POST">
            <textarea name="quote" placeholder="Testimonial Quote" required></textarea>
            <input type="text" name="author" placeholder="Author" required>
            <button type="submit" name="add_testimonial">Add Testimonial</button>
        </form>

        <!-- List of Testimonials -->
        <div class="testimonials-list">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="testimonial-item">
                    <p>"<?php echo htmlspecialchars($testimonial['quote']); ?>"</p>
                    <span>- <?php echo htmlspecialchars($testimonial['author']); ?></span>
                    <a href="edit-testimonials.php?delete=<?php echo $testimonial['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>