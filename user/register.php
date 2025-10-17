<?php
require_once '../includes/config.php';
require_once '../includes/security.php';

$error = '';
$success = '';

// Generate CSRF token
$csrf_token = generateCSRFToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error = "Invalid request. Please try again.";
    } else {
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $course = sanitizeInput($_POST['course']);
        $semester = sanitizeInput($_POST['semester']);
        
        // Validation
        if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($course) || empty($semester)) {
            $error = "All fields are required.";
        } elseif (!validateEmail($email)) {
            $error = "Please enter a valid email address.";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters long.";
        } else {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "Email already registered. Please use a different email.";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert user into database
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, course, semester) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $name, $email, $hashed_password, $course, $semester);
                
                if ($stmt->execute()) {
                    $success = "Registration successful! You can now login.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
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
    <title>Register - Online Notes Sharing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Register</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo escapeHtml($error); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo escapeHtml($success); ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="course" class="form-label">Course</label>
                                <input type="text" class="form-control" id="course" name="course" placeholder="e.g., B.Tech, MCA, etc." required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                <input type="text" class="form-control" id="semester" name="semester" placeholder="e.g., Semester 1, 2nd Year, etc." required>
                            </div>
                            
                            <div class="d-grid">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="login.php">Login here</a></p>
                        </div>
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