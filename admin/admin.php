<?php
require_once '../includes/config.php';
require_once '../includes/security.php';
requireAdmin();

// Get statistics
$users_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$notes_count = $conn->query("SELECT COUNT(*) as count FROM notes")->fetch_assoc()['count'];
$downloads_count = $conn->query("SELECT COUNT(*) as count FROM downloads")->fetch_assoc()['count'];

// Get all users
$users_result = $conn->query("SELECT * FROM users ORDER BY user_id DESC");
$users = $users_result->fetch_all(MYSQLI_ASSOC);

// Get all notes with user info
$notes_result = $conn->query("SELECT n.*, s.subject_name, u.name as uploader_name FROM notes n JOIN subjects s ON n.subject_id = s.subject_id JOIN users u ON n.uploaded_by = u.user_id ORDER BY n.upload_date DESC");
$notes = $notes_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Online Notes Sharing System</title>
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
                        <a class="nav-link" href="../notes/upload.php">Upload Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../notes/mynotes.php">My Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../notes/search.php">Search Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin.php">Admin Panel</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="navbar-text me-3">Hello, <?php echo escapeHtml($_SESSION['name']); ?> (Admin)</span>
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
                <h2>Admin Panel</h2>
                <p>Manage users and notes in the system.</p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total Users</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $users_count; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Total Notes</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $notes_count; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Total Downloads</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $downloads_count; ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Management -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card border-sky-blue">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-users me-2"></i>Manage Users</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-sky-blue">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Course</th>
                                        <th>Semester</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user['user_id']; ?></td>
                                            <td><?php echo escapeHtml($user['name']); ?></td>
                                            <td><?php echo escapeHtml($user['email']); ?></td>
                                            <td><?php echo escapeHtml($user['course']); ?></td>
                                            <td><?php echo escapeHtml($user['semester']); ?></td>
                                            <td><?php echo ucfirst($user['role']); ?></td>
                                            <td>
                                                <?php if ($user['role'] !== 'admin'): ?>
                                                    <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user? This will also delete all their notes.')">Delete</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Management -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card border-sky-blue">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Manage Notes</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-sky-blue">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Subject</th>
                                        <th>Uploaded By</th>
                                        <th>Upload Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($notes as $note): ?>
                                        <tr>
                                            <td><?php echo $note['note_id']; ?></td>
                                            <td><?php echo escapeHtml($note['title']); ?></td>
                                            <td><?php echo escapeHtml($note['subject_name']); ?></td>
                                            <td><?php echo escapeHtml($note['uploader_name']); ?></td>
                                            <td><?php echo formatDate($note['upload_date']); ?></td>
                                            <td>
                                                <a href="../notes/download.php?id=<?php echo $note['note_id']; ?>" class="btn btn-sm btn-success">Download</a>
                                                <a href="delete_note_admin.php?id=<?php echo $note['note_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this note?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Sky Blue color definitions */
        .text-sky-blue {
            color: #87CEEB !important;
        }
        
        .bg-sky-blue {
            background-color: #87CEEB !important;
        }
        
        .border-sky-blue {
            border-color: #87CEEB !important;
        }
        
        .table-sky-blue th {
            background-color: #87CEEB;
            color: #0a0a14;
        }
        
        .card.border-sky-blue {
            border-width: 2px;
        }
        
        .btn-sky-blue {
            background-color: #87CEEB;
            border-color: #87CEEB;
            color: #0a0a14;
        }
        
        .btn-sky-blue:hover {
            background-color: #5fa8d3;
            border-color: #5fa8d3;
        }
        
        /* Enhanced table styling improvements */
        .table {
            color: #ffffff;
            font-weight: 500;
        }
        
        .table th {
            font-weight: 700;
            color: #0a0a14;
            background-color: #87CEEB;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }
        
        .table td {
            color: #ffffff;
            border-top: 1px solid rgba(135, 206, 235, 0.3);
            padding: 12px 8px;
            font-weight: 500;
            text-shadow: 0 0 6px rgba(0, 0, 0, 0.9);
            background-color: rgba(30, 30, 50, 0.95);
        }
        
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(40, 40, 70, 0.95);
        }
        
        .table-striped > tbody > tr:nth-of-type(odd) td {
            background-color: rgba(40, 40, 70, 0.95);
            color: #ffffff;
        }
        
        .table-striped > tbody > tr:nth-of-type(even) {
            background-color: rgba(30, 30, 50, 0.95);
        }
        
        .table-striped > tbody > tr:nth-of-type(even) td {
            background-color: rgba(30, 30, 50, 0.95);
            color: #ffffff;
        }
        
        .table-hover > tbody > tr:hover {
            background-color: rgba(135, 206, 235, 0.7);
        }
        
        .table-hover > tbody > tr:hover td {
            background-color: rgba(135, 206, 235, 0.8);
            color: #ffffff;
            text-shadow: 0 0 8px rgba(0, 0, 0, 0.9);
        }
        
        .table a {
            color: #87CEEB;
            text-decoration: none;
            font-weight: 600;
            padding: 3px 6px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        
        .table a:hover {
            color: #ffffff;
            background-color: #87CEEB;
            text-decoration: none;
        }
        
        .table .btn {
            margin: 2px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            font-weight: 600;
            padding: 5px 10px;
            border: none;
        }
    </style>
</body>
</html>