<?php
require_once '../includes/config.php';
require_once '../includes/security.php';
requireAdmin();

// Get note ID from URL
$note_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($note_id <= 0) {
    header("Location: admin.php");
    exit();
}

// Validate note ID
if (!validateID($note_id)) {
    header("Location: admin.php");
    exit();
}

// Get note file path before deletion
$stmt = $conn->prepare("SELECT file_path FROM notes WHERE note_id = ?");
$stmt->bind_param("i", $note_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: admin.php");
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
    
    $_SESSION['message'] = "Note deleted successfully.";
} else {
    $_SESSION['error'] = "Error deleting note. Please try again.";
}
$stmt->close();

header("Location: admin.php");
exit();
?>