<?php
require_once '../includes/config.php';
require_once '../includes/security.php';
requireLogin();

// Get note ID from URL
$note_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($note_id <= 0) {
    header("Location: ../dashboard.php");
    exit();
}

// Validate note ID
if (!validateID($note_id)) {
    header("Location: ../dashboard.php");
    exit();
}

// Get note file path
$stmt = $conn->prepare("SELECT file_path, title FROM notes WHERE note_id = ?");
$stmt->bind_param("i", $note_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../dashboard.php");
    exit();
}

$note = $result->fetch_assoc();
$file_path = $note['file_path'];
$filename = basename($file_path);
$stmt->close();

// Record download
$stmt = $conn->prepare("INSERT INTO downloads (user_id, note_id) VALUES (?, ?)");
$stmt->bind_param("ii", $_SESSION['user_id'], $note_id);
$stmt->execute();
$stmt->close();

// Check if file exists
if (file_exists($file_path)) {
    // Set headers for download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    
    // Clear output buffer
    ob_clean();
    flush();
    
    // Read the file and output it
    readfile($file_path);
    exit;
} else {
    // File not found
    $_SESSION['error'] = "File not found.";
    header("Location: ../dashboard.php");
    exit();
}
?>