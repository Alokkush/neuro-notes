<?php
// Security functions for the Online Notes Sharing System

// Prevent direct access to files
function preventDirectAccess() {
    if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
        header("Location: index.php");
        exit();
    }
}

// Function to validate file uploads
function validateFileUpload($file) {
    $allowed_types = array("pdf", "doc", "docx", "ppt", "pptx");
    $max_size = 10000000; // 10MB
    
    // Check if file was uploaded without errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "File upload error.";
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        return "File size exceeds 10MB limit.";
    }
    
    // Check file extension
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_types)) {
        return "Invalid file type. Only PDF, DOC, DOCX, PPT, PPTX files are allowed.";
    }
    
    return true;
}

// Function to generate secure file name
function generateSecureFileName($original_name) {
    $extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $name = pathinfo($original_name, PATHINFO_FILENAME);
    // Remove special characters and spaces
    $name = preg_replace("/[^a-zA-Z0-9_-]/", "", $name);
    // Generate unique name
    return uniqid() . "_" . $name . "." . $extension;
}

// CSRF Protection
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// XSS Prevention for output
function escapeHtml($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Validate integer ID
function validateID($id) {
    return filter_var($id, FILTER_VALIDATE_INT) && $id > 0;
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>