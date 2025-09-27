<?php
require_once 'auth_middleware.php';
require_once '../config/config.php';

$message = '';
$error = '';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $media_urls = [];
        $current_media_url = $_POST['current_media_url'] ?? '';

        // Handle file uploads
        if (isset($_FILES['media']['name']) && is_array($_FILES['media']['name'])) {
            $target_dir = "../public/images/projects/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            foreach ($_FILES['media']['name'] as $key => $name) {
                if ($_FILES['media']['error'][$key] == 0) {
                    $target_file = $target_dir . basename($name);
                    if (move_uploaded_file($_FILES['media']['tmp_name'][$key], $target_file)) {
                        $media_urls[] = "public/images/projects/" . basename($name);
                    } else {
                        $error = "Sorry, there was an error uploading one of your files.";
                    }
                }
            }
        }

        $new_media_urls = implode(',', $media_urls);
        $final_media_url = $current_media_url;

        if (!empty($new_media_urls)) {
            $final_media_url = !empty($final_media_url) ? $final_media_url . ',' . $new_media_urls : $new_media_urls;
        }

        if (empty($error)) {
            if (isset($_POST['add_project'])) {
                $stmt = $pdo->prepare('INSERT INTO projects (title, description, media_url) VALUES (?, ?, ?)');
                $stmt->execute([$_POST['title'], $_POST['description'], $final_media_url]);
                header('Location: edit-projects.php?success=Project added successfully!');
                exit;
            } elseif (isset($_POST['update_project'])) {
                $stmt = $pdo->prepare('UPDATE projects SET title = ?, description = ?, media_url = ? WHERE id = ?');
                $stmt->execute([$_POST['title'], $_POST['description'], $final_media_url, $_POST['id']]);
                header('Location: edit-projects.php?success=Project updated successfully!');
                exit;
            } elseif (isset($_POST['delete_project'])) {
                $stmt = $pdo->prepare('DELETE FROM projects WHERE id = ?');
                $stmt->execute([$_POST['id']]);
                header('Location: edit-projects.php?success=Project deleted successfully!');
                exit;
            }
        }
    }

    $projects = $pdo->query('SELECT * FROM projects ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Projects</title>
    <link rel="stylesheet" href="../public/admin-style.css">
</head>
<body>

<?php include 'admin-header.php'; ?>

<div class="main-content">
    <h1>Edit Projects</h1>

    <div class="form-container">
        <h3>Add New Project</h3>
        <form action="edit-projects.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"></textarea>
            </div>
            <div class="form-group">
                <label>Media (Images/Videos)</label>
                <input type="file" name="media[]" multiple>
            </div>
            <button type="submit" name="add_project">Add Project</button>
        </form>
    </div>

    <div class="item-list">
        <h3>Existing Projects</h3>
        <?php foreach ($projects as $project): ?>
            <div class="item">
                <form action="edit-projects.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                    <input type="hidden" name="current_media_url" value="<?php echo htmlspecialchars($project['media_url']); ?>">
                    <input type="text" name="title" value="<?php echo htmlspecialchars($project['title']); ?>">
                    <textarea name="description"><?php echo htmlspecialchars($project['description']); ?></textarea>
                    <div>
                        <?php if ($project['media_url']): ?>
                            <p>Current Media: <?php echo htmlspecialchars($project['media_url']); ?></p>
                        <?php endif; ?>
                        <label>Upload New Media</label>
                        <input type="file" name="media[]" multiple>
                    </div>
                    <button type="submit" name="update_project">Update</button>
                    <button type="submit" name="delete_project" class="delete" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>