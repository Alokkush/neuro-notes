<?php
require_once '../includes/config.php';
require_once '../includes/security.php';
requireLogin();

// Get user's notes
$stmt = $conn->prepare("SELECT n.*, s.subject_name FROM notes n JOIN subjects s ON n.subject_id = s.subject_id WHERE n.uploaded_by = ? ORDER BY n.upload_date DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$userNotes = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notes - Online Notes Sharing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        <a class="nav-link active" href="mynotes.php">My Notes</a>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card border-sky-blue">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Your Notes</h4>
                    </div>
                    <div class="card-body">
                        <?php if (count($userNotes) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-sky-blue">
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Subject</th>
                                            <th>Upload Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($userNotes as $note): ?>
                                            <tr>
                                                <td><?php echo escapeHtml($note['title']); ?></td>
                                                <td><?php echo escapeHtml(substr($note['description'], 0, 100)) . (strlen($note['description']) > 100 ? '...' : ''); ?></td>
                                                <td><?php echo escapeHtml($note['subject_name']); ?></td>
                                                <td><?php echo formatDate($note['upload_date']); ?></td>
                                                <td>
                                                    <a href="download.php?id=<?php echo $note['note_id']; ?>" class="btn btn-sm btn-success">Download</a>
                                                    <a href="edit_note.php?id=<?php echo $note['note_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                    <a href="delete_note.php?id=<?php echo $note['note_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this note?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>You haven't uploaded any notes yet. <a href="upload.php">Upload your first note</a>.</p>
                        <?php endif; ?>
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
    <style>
        /* Enhanced table styling for better visibility */
        .table td {
            text-shadow: 0 0 6px rgba(0, 0, 0, 0.9);
            background-color: rgba(30, 30, 50, 0.95);
        }
        
        .table-striped > tbody > tr:nth-of-type(odd) td {
            background-color: rgba(40, 40, 70, 0.95);
            color: #ffffff;
        }
        
        .table-striped > tbody > tr:nth-of-type(even) td {
            background-color: rgba(30, 30, 50, 0.95);
            color: #ffffff;
        }
        
        .table-hover > tbody > tr:hover td {
            background-color: rgba(135, 206, 235, 0.8);
            color: #ffffff;
            text-shadow: 0 0 8px rgba(0, 0, 0, 0.9);
        }
    </style>
</body>
</html>