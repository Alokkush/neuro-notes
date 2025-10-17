<?php
require_once '../includes/config.php';
require_once '../includes/security.php';
requireLogin();

$error = '';
$success = '';

// Generate CSRF token
$csrf_token = generateCSRFToken();

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
        } elseif (!isset($_FILES['note_file']) || $_FILES['note_file']['error'] === UPLOAD_ERR_NO_FILE) {
            $error = "Please select a file to upload.";
        } else {
            // Validate file upload
            $file_validation = validateFileUpload($_FILES['note_file']);
            if ($file_validation !== true) {
                $error = $file_validation;
            } else {
                // File upload handling
                $target_dir = "../uploads/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                
                // Generate secure file name
                $secure_file_name = generateSecureFileName($_FILES["note_file"]["name"]);
                $target_file = $target_dir . $secure_file_name;
                $uploadOk = 1;
                
                // Check if file already exists
                if (file_exists($target_file)) {
                    $error = "Sorry, there was an error uploading your file. Please try again.";
                    $uploadOk = 0;
                }
                
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk === 1) {
                    if (move_uploaded_file($_FILES["note_file"]["tmp_name"], $target_file)) {
                        // Insert note into database
                        $stmt = $conn->prepare("INSERT INTO notes (title, description, file_path, subject_id, uploaded_by) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssii", $title, $description, $target_file, $subject_id, $_SESSION['user_id']);
                        
                        if ($stmt->execute()) {
                            $success = "The file ". escapeHtml(basename($_FILES["note_file"]["name"])). " has been uploaded successfully.";
                        } else {
                            $error = "Sorry, there was an error uploading your file.";
                            // Delete uploaded file if database insert fails
                            if (file_exists($target_file)) {
                                unlink($target_file);
                            }
                        }
                        $stmt->close();
                    } else {
                        $error = "Sorry, there was an error uploading your file.";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Notes - Online Notes Sharing System</title>
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
                        <a class="nav-link active" href="upload.php">Upload Notes</a>
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
                        <h3>Upload New Note</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo escapeHtml($error); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo escapeHtml($success); ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Note Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject_id" class="form-label">Subject</label>
                                <select class="form-select" id="subject_id" name="subject_id" required>
                                    <option value="">Select Subject</option>
                                    <?php foreach ($subjects as $subject): ?>
                                        <option value="<?php echo $subject['subject_id']; ?>"><?php echo escapeHtml($subject['subject_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="note_file" class="form-label">Choose File</label>
                                <input class="form-control" type="file" id="note_file" name="note_file" required>
                                <div class="form-text text-white">Allowed file types: PDF, DOC, DOCX, PPT, PPTX. Max file size: 10MB.</div>
                            </div>
                            
                            <div class="d-grid">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <button type="submit" class="btn btn-primary">Upload Note</button>
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