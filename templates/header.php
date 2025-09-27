<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($about['name'] ?? 'Portfolio'); ?>'s Portfolio</title>
    <link rel="stylesheet" href="public/new-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <!-- Sidebar (Desktop) -->
    <aside class="sidebar">
        <img src="<?php echo htmlspecialchars($about['photo_url'] ?? 'public/default-profile.png'); ?>" alt="Profile Picture" class="profile-pic">
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About Me</a></li>
                <li><a href="#education">Education</a></li>
                <li><a href="#experience">Experience</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#testimonials">Testimonials</a></li>
                <li><a href="#download">Downloads</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <a href="admin/login.php" class="admin-login-link">⚙️ Admin Login</a>
    </aside>

    <!-- Mobile Header -->
    <header class="mobile-header">
        <button class="menu-btn">☰</button>
        <span><?php echo htmlspecialchars($about['name'] ?? 'Portfolio'); ?></span>
        <button class="theme-toggle">🌙</button>
    </header>

    <!-- Mobile Drawer -->
    <aside class="drawer">
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About Me</a></li>
                <li><a href="#education">Education</a></li>
                <li><a href="#experience">Experience</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#testimonials">Testimonials</a></li>
                <li><a href="#download">Downloads</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-panel">