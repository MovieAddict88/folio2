<?php
// This assumes auth_middleware.php has been included by the parent page.
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>
<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="edit-about.php">About Me</a></li>
        <li><a href="edit-experience.php">Experience</a></li>
        <li><a href="edit-skills.php">Skills</a></li>
        <li><a href="edit-projects.php">Projects</a></li>
        <li><a href="edit-testimonials.php">Testimonials</a></li>
        <li><a href="manage-documents.php">Documents</a></li>
        <li><a href="manage-passwords.php">Download Passwords</a></li>
        <li><a href="settings.php">Settings</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    <hr>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['success']); ?></p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
</div>