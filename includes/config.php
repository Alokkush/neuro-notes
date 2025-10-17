<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'online_notes_db');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to prevent XSS
$conn->set_charset("utf8mb4");

// Include security functions
require_once 'security.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    // Debug output
    if (isset($_GET['debug'])) {
        error_log("isLoggedIn() called - user_id set: " . (isset($_SESSION['user_id']) ? 'true' : 'false'));
    }
    
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function isAdmin() {
    // Debug output
    if (isset($_GET['debug'])) {
        error_log("isAdmin() called - role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set'));
    }
    
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Function to redirect to login page if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        // Debug output
        if (isset($_GET['debug'])) {
            error_log("requireLogin() redirecting to ../user/login.php");
        }
        header("Location: ../user/login.php");
        exit();
    }
}

// Function to redirect to home page if not admin
function requireAdmin() {
    if (!isLoggedIn()) {
        // Debug output
        if (isset($_GET['debug'])) {
            error_log("requireAdmin() redirecting to ../user/login.php (not logged in)");
        }
        header("Location: ../user/login.php");
        exit();
    }
    
    if (!isAdmin()) {
        // Debug output
        if (isset($_GET['debug'])) {
            error_log("requireAdmin() redirecting to ../index.php (not admin)");
        }
        header("Location: ../index.php");
        exit();
    }
}

// Function to sanitize input data
function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

// Function to format date
function formatDate($date) {
    return date('M j, Y g:i A', strtotime($date));
}
?>