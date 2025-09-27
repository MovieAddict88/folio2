<?php
require_once 'auth_middleware.php';
require_once '../config/config.php';

$message = '';
$error = '';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $photo_url = $_POST['current_photo_url']; // Keep old photo if new one isn't uploaded
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $target_dir = "../public/images/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if($check !== false) {
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    $photo_url = "public/images/" . basename($_FILES["photo"]["name"]);
                } else {
                    $error = "Sorry, there was an error uploading your file.";
                }
            } else {
                $error = "File is not an image.";
            }
        }

        if (empty($error)) {
            $stmt = $pdo->prepare('UPDATE about_me SET name = ?, photo_url = ?, tagline = ?, bio = ?, education = ?, philosophy = ?, email = ?, linkedin_url = ?, phone = ?, address = ?, facebook_url = ?, youtube_url = ?, tiktok_url = ? WHERE id = 1');
            $stmt->execute([
                $_POST['name'], $photo_url, $_POST['tagline'], $_POST['bio'], $_POST['education'], $_POST['philosophy'],
                $_POST['email'], $_POST['linkedin_url'], $_POST['phone'], $_POST['address'], $_POST['facebook_url'],
                $_POST['youtube_url'], $_POST['tiktok_url']
            ]);
            header('Location: edit-about.php?success=About Me section updated successfully!');
            exit;
        }
    }

    // Fetch current data
    $stmt = $pdo->query('SELECT * FROM about_me WHERE id = 1');
    $about = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit About Me</title>
    <link rel="stylesheet" href="../public/admin-style.css">
</head>
<body>

<?php include 'admin-header.php'; ?>

<div class="main-content">
    <h1>Edit About Me</h1>
    <form action="edit-about.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($about['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="tagline">Tagline</label>
            <input type="text" id="tagline" name="tagline" value="<?php echo htmlspecialchars($about['tagline']); ?>">
        </div>
        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" id="photo" name="photo">
            <input type="hidden" name="current_photo_url" value="<?php echo htmlspecialchars($about['photo_url']); ?>">
            <?php if(!empty($about['photo_url'])): ?>
                <p>Current photo: <img src="../<?php echo htmlspecialchars($about['photo_url']); ?>" alt="Current Photo" width="100"></p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio"><?php echo htmlspecialchars($about['bio']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="education">Education</label>
            <textarea id="education" name="education"><?php echo htmlspecialchars($about['education']); ?></textarea>
            <small>Enter each qualification on a new line.</small>
        </div>
        <div class="form-group">
            <label for="philosophy">Teaching Philosophy</label>
            <textarea id="philosophy" name="philosophy"><?php echo htmlspecialchars($about['philosophy']); ?></textarea>
        </div>
        <hr>
        <h3>Contact Information</h3>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($about['email']); ?>">
        </div>
        <div class="form-group">
            <label for="linkedin_url">LinkedIn URL</label>
            <input type="text" id="linkedin_url" name="linkedin_url" value="<?php echo htmlspecialchars($about['linkedin_url']); ?>">
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($about['phone']); ?>">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($about['address'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="facebook_url">Facebook URL</label>
            <input type="text" id="facebook_url" name="facebook_url" value="<?php echo htmlspecialchars($about['facebook_url'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="youtube_url">YouTube URL</label>
            <input type="text" id="youtube_url" name="youtube_url" value="<?php echo htmlspecialchars($about['youtube_url'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="tiktok_url">TikTok URL</label>
            <input type="text" id="tiktok_url" name="tiktok_url" value="<?php echo htmlspecialchars($about['tiktok_url'] ?? ''); ?>">
        </div>
        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>