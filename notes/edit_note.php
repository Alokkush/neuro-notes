<?php
require_once '../includes/config.php';
require_once '../includes/security.php';
requireLogin();

$error = '';
$success = '';

// Generate CSRF token
$csrf_token = generateCSRFToken();

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
$stmt = $conn->prepare("SELECT n.*, s.subject_name FROM notes n JOIN subjects s ON n.subject_id = s.subject_id WHERE n.note_id = ? AND (n.uploaded_by = ? OR ? = 'admin')");
$stmt->bind_param("iis", $note_id, $_SESSION['user_id'], $_SESSION['role']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: mynotes.php");
    exit();
}

$note = $result->fetch_assoc();
$stmt->close();

// Get subjects for dropdown
$stmt = $conn->prepare("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");
$stmt->execute();
$result = $stmt->get_result();
$subjects = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error = "Invalid request. Please try again.";
    } else {
        $title = sanitizeInput($_POST['title']);
        $description = sanitizeInput($_POST['description']);
        $subject_id = intval($_POST['subject_id']);
        
        // Validation
        if (empty($title) || empty($description) || empty($subject_id)) {
            $error = "All fields are required.";
        } else {
            // Update note in database
            $stmt = $conn->prepare("UPDATE notes SET title = ?, description = ?, subject_id = ? WHERE note_id = ?");
            $stmt->bind_param("ssii", $title, $description, $subject_id, $note_id);
            
            if ($stmt->execute()) {
                $success = "Note updated successfully.";
                // Refresh note data
                $stmt = $conn->prepare("SELECT n.*, s.subject_name FROM notes n JOIN subjects s ON n.subject_id = s.subject_id WHERE n.note_id = ?");
                $stmt->bind_param("i", $note_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $note = $result->fetch_assoc();
                $stmt->close();
            } else {
                $error = "Error updating note. Please try again.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note - Online Notes Sharing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../dashboard.php">Online Notes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="upload.php">Upload Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mynotes.php">My Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">Search Notes</a>
                    </li>
                    <?php if (isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/admin.php">Admin Panel</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="navbar-text me-3">Hello, <?php echo escapeHtml($_SESSION['name']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../user/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Edit Note</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo escapeHtml($error); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo escapeHtml($success); ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="title" class="form-label">Note Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo escapeHtml($note['title']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo escapeHtml($note['description']); ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject_id" class="form-label">Subject</label>
                                <select class="form-select" id="subject_id" name="subject_id" required>
                                    <option value="">Select Subject</option>
                                    <?php foreach ($subjects as $subject): ?>
                                        <option value="<?php echo $subject['subject_id']; ?>" <?php echo ($subject['subject_id'] == $note['subject_id']) ? 'selected' : ''; ?>>
                                            <?php echo escapeHtml($subject['subject_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Current File</label>
                                <div>
                                    <a href="download.php?id=<?php echo $note['note_id']; ?>" class="btn btn-success btn-sm">
                                        Download Current File
                                    </a>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <a href="mynotes.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Note</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">Online Notes Sharing System &copy; 2025</span>
        </div>
    </footer>
</body>
</html>