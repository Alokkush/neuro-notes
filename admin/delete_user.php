<?php
require_once '../includes/config.php';
require_once '../includes/security.php';
requireAdmin();

// Get user ID from URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id <= 0 || $user_id == $_SESSION['user_id']) {
    header("Location: admin.php");
    exit();
}

// Validate user ID
if (!validateID($user_id)) {
    header("Location: admin.php");
    exit();
}

// Check if user exists and is not an admin
$stmt = $conn->prepare("SELECT user_id, role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0 || $result->fetch_assoc()['role'] === 'admin') {
    header("Location: admin.php");
    exit();
}
$stmt->close();

// Delete user (this will cascade delete their notes and downloads)
$stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "User deleted successfully.";
} else {
    $_SESSION['error'] = "Error deleting user. Please try again.";
}
$stmt->close();

header("Location: admin.php");
exit();
?>