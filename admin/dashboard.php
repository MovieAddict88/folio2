<?php
require_once 'auth_middleware.php'; // Protect this page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../public/admin-style.css">
</head>
<body>

<div class="main-container">
    <?php include 'admin-header.php'; ?>
    <div class="main-content">
        <h2>Portfolio Overview</h2>
        <p>Welcome to the admin panel. From here, you can manage all the content on your portfolio website.</p>
        <p>Use the navigation on the left to edit different sections of your portfolio.</p>
    </div>
</div>

</body>
</html>