<?php
require_once '../includes/config.php';
require_once '../includes/security.php';
requireLogin();

// Get note ID from URL
$note_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($note_id <= 0) {
    header("Location: mynotes.php");
    exit();
}

// Validate note ID
if (!validateID($note_id)) {
    header("Location: mynotes.php");
    exit();
}

// Check if note belongs to current user or user is admin
$stmt = $conn->prepare("SELECT file_path FROM notes WHERE note_id = ? AND (uploaded_by = ? OR ? = 'admin')");
$stmt->bind_param("iis", $note_id, $_SESSION['user_id'], $_SESSION['role']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: mynotes.php");
    exit();
}

$note = $result->fetch_assoc();
$file_path = $note['file_path'];
$stmt->close();

// Delete note from database
$stmt = $conn->prepare("DELETE FROM notes WHERE note_id = ?");
$stmt->bind_param("i", $note_id);

if ($stmt->execute()) {
    // Delete file from server
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    
    // Redirect with success message
    $_SESSION['message'] = "Note deleted successfully.";
    header("Location: mynotes.php");
} else {
    // Redirect with error message
    $_SESSION['error'] = "Error deleting note. Please try again.";
    header("Location: mynotes.php");
}
$stmt->close();
?>