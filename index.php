<?php
require_once 'config/config.php';

// --- Fetch all portfolio data ---
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch About Me data (which includes contact info)
    $about_stmt = $pdo->query('SELECT * FROM about_me LIMIT 1');
    $about = $about_stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch Experience data
    $exp_stmt = $pdo->query('SELECT * FROM experience ORDER BY start_year DESC');
    $experiences = $exp_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch Skills data
    $skills_stmt = $pdo->query('SELECT * FROM skills ORDER BY category, level DESC');
    $skills = $skills_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch Projects data
    $projects_stmt = $pdo->query('SELECT * FROM projects');
    $projects = $projects_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch Testimonials data (new)
    $testimonials_stmt = $pdo->query('SELECT * FROM testimonials');
    $testimonials = $testimonials_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch Documents data
    $documents_stmt = $pdo->query('SELECT * FROM documents ORDER BY file_name ASC');
    $documents = $documents_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: Could not connect to the database. Please ensure the installer has run and the config file is correct. " . $e->getMessage());
}

// Include header
include 'templates/header.php';

// Include sections
include 'templates/home.php';
include 'templates/about.php';
include 'templates/education.php';
include 'templates/experience.php';
include 'templates/skills.php';
include 'templates/projects.php';
include 'templates/testimonials.php';
include 'templates/download.php';
include 'templates/contact.php';

// Include footer
include 'templates/footer.php';
?>