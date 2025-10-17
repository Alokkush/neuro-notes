<?php
require_once '../includes/config.php';
require_once '../includes/security.php';
requireLogin();

$search_term = '';
$subject_filter = '';
$notes = [];

// Get subjects for filter dropdown
$stmt = $conn->prepare("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");
$stmt->execute();
$result = $stmt->get_result();
$subjects = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle search
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['search']) || isset($_GET['subject_id']))) {
    $search_term = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
    $subject_filter = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : '';
    
    // Validate subject filter if provided
    if (!empty($subject_filter) && !validateID($subject_filter)) {
        $subject_filter = '';
    }
    
    // Build query based on search criteria
    $sql = "SELECT n.*, s.subject_name, u.name as uploader_name FROM notes n 
            JOIN subjects s ON n.subject_id = s.subject_id 
            JOIN users u ON n.uploaded_by = u.user_id 
            WHERE 1=1";
    
    $params = [];
    $types = "";
    
    if (!empty($search_term)) {
        $sql .= " AND (n.title LIKE ? OR n.description LIKE ?)";
        $params[] = "%{$search_term}%";
        $params[] = "%{$search_term}%";
        $types .= "ss";
    }
    
    if (!empty($subject_filter)) {
        $sql .= " AND n.subject_id = ?";
        $params[] = $subject_filter;
        $types .= "i";
    }
    
    $sql .= " ORDER BY n.upload_date DESC";
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $notes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Notes - Online Notes Sharing System</title>
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
                        <a class="nav-link" href="mynotes.php">My Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="search.php">Search Notes</a>
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
                <h2>Search Notes</h2>
                <p>Find notes by title, description, or subject.</p>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-6">
                                <label for="search" class="form-label">Search Term</label>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Enter title or description" value="<?php echo escapeHtml($search_term); ?>">
                            </div>
                            
                            <div class="col-md-4">
                                <label for="subject_id" class="form-label">Filter by Subject</label>
                                <select class="form-select" id="subject_id" name="subject_id">
                                    <option value="">All Subjects</option>
                                    <?php foreach ($subjects as $subject): ?>
                                        <option value="<?php echo $subject['subject_id']; ?>" <?php echo ($subject_filter == $subject['subject_id']) ? 'selected' : ''; ?>>
                                            <?php echo escapeHtml($subject['subject_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <?php if (isset($_GET['search']) || isset($_GET['subject_id'])): ?>
                    <div class="card border-sky-blue">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="fas fa-search me-2"></i>Search Results</h4>
                        </div>
                        <div class="card-body">
                            <?php if (count($notes) > 0): ?>
                                <p>Found <?php echo count($notes); ?> note(s).</p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-sky-blue">
                                            <tr>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Subject</th>
                                                <th>Uploaded By</th>
                                                <th>Upload Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($notes as $note): ?>
                                                <tr>
                                                    <td><?php echo escapeHtml($note['title']); ?></td>
                                                    <td><?php echo escapeHtml(substr($note['description'], 0, 100)) . (strlen($note['description']) > 100 ? '...' : ''); ?></td>
                                                    <td><?php echo escapeHtml($note['subject_name']); ?></td>
                                                    <td><?php echo escapeHtml($note['uploader_name']); ?></td>
                                                    <td><?php echo formatDate($note['upload_date']); ?></td>
                                                    <td>
                                                        <a href="download.php?id=<?php echo $note['note_id']; ?>" class="btn btn-sm btn-success">Download</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p>No notes found matching your search criteria.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
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