<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust for production
echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);
?>