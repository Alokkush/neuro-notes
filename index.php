<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Notes Sharing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-dark text-light">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="fas fa-brain text-primary me-2"></i>
                <span class="fw-bold">Neuro<span class="text-primary">Notes</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php"><i class="fas fa-chart-line me-1"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="user/logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="user/login.php"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="user/register.php"><i class="fas fa-user-plus me-1"></i> Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary ms-2" href="admin/admin.php"><i class="fas fa-user-shield me-1"></i> Admin</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container-fluid vh-100 d-flex align-items-center" style="background: radial-gradient(circle, rgba(30,30,46,1) 0%, rgba(10,10,20,1) 100%);">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-2 fw-bold mb-3">
                        <span class="d-block">Future of</span>
                        <span class="text-primary">Knowledge</span>
                        <span class="d-block">Sharing</span>
                    </h1>
                    <p class="lead text-muted mb-4">Revolutionizing how students collaborate and access educational resources through intelligent note sharing platform.</p>
                    
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <div class="mt-4">
                            <a href="user/register.php" class="btn btn-primary btn-lg px-4 py-3 me-3">
                                <i class="fas fa-rocket me-2"></i>Join the Future
                            </a>
                            <a href="user/login.php" class="btn btn-outline-light btn-lg px-4 py-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="mt-4">
                            <a href="dashboard.php" class="btn btn-primary btn-lg px-4 py-3">
                                <i class="fas fa-rocket me-2"></i>Access Dashboard
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6 d-none d-lg-block position-relative">
                    <div class="floating-animation">
                        <div class="cube cube-1"></div>
                        <div class="cube cube-2"></div>
                        <div class="cube cube-3"></div>
                    </div>
                </div>
            </div>
            
            <!-- Animated Stats -->
            <div class="row mt-5">
                <div class="col-md-4 col-6 text-center">
                    <div class="stat-card p-3 rounded-3">
                        <i class="fas fa-users fa-2x text-primary mb-2"></i>
                        <h3 class="mb-0">10K+</h3>
                        <p class="text-muted mb-0">Active Users</p>
                    </div>
                </div>
                <div class="col-md-4 col-6 text-center">
                    <div class="stat-card p-3 rounded-3">
                        <i class="fas fa-file-alt fa-2x text-success mb-2"></i>
                        <h3 class="mb-0">50K+</h3>
                        <p class="text-muted mb-0">Notes Shared</p>
                    </div>
                </div>
                <div class="col-md-4 col-6 text-center">
                    <div class="stat-card p-3 rounded-3">
                        <i class="fas fa-graduation-cap fa-2x text-warning mb-2"></i>
                        <h3 class="mb-0">100+</h3>
                        <p class="text-muted mb-0">Institutions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container my-5 py-5">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="fw-bold display-4 mb-3">Next-Gen <span class="text-primary">Features</span></h2>
                <p class="text-muted lead">Designed with cutting-edge technology for the modern student</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card bg-dark border-0 h-100 feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-shield-alt fa-2x"></i>
                        </div>
                        <h5 class="card-title fw-bold">Quantum Security</h5>
                        <p class="card-text text-muted">Military-grade encryption protects your data with blockchain-level security protocols.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-dark border-0 h-100 feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-bolt fa-2x"></i>
                        </div>
                        <h5 class="card-title fw-bold">Instant Sync</h5>
                        <p class="card-text text-muted">Real-time collaboration with zero-latency synchronization across all devices.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-dark border-0 h-100 feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-robot fa-2x"></i>
                        </div>
                        <h5 class="card-title fw-bold">Smart Summarization</h5>
                        <p class="card-text text-muted">AI automatically generates concise summaries and key points from lengthy documents.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-4 mt-3">
            <div class="col-md-6">
                <div class="card bg-dark border-0 h-100 feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-vr-cardboard fa-2x"></i>
                        </div>
                        <h5 class="card-title fw-bold">Immersive Learning</h5>
                        <p class="card-text text-muted">Experience 3D visualizations and AR-enhanced learning materials for complex topics.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card bg-dark border-0 h-100 feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-danger bg-opacity-10 text-danger rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-download fa-2x"></i>
                        </div>
                        <h5 class="card-title fw-bold">Easy Access</h5>
                        <p class="card-text text-muted">Seamless access from any device with intelligent storage and backup.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="d-flex align-items-center">
                        <i class="fas fa-brain text-sky-blue me-2"></i>
                        <span>Neuro<span class="text-sky-blue">Notes</span></span>
                    </h5>
                    <p class="text-light">Revolutionizing education through intelligent knowledge sharing and collaborative learning.</p>
                </div>
                <div class="col-md-3">
                    <h5 class="text-sky-blue">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="user/register.php" class="text-light text-decoration-none"><i class="fas fa-user-plus me-1"></i> Register</a></li>
                        <li><a href="user/login.php" class="text-light text-decoration-none"><i class="fas fa-sign-in-alt me-1"></i> Login</a></li>
                        <li><a href="admin/admin.php" class="text-light text-decoration-none"><i class="fas fa-user-shield me-1"></i> Admin</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="text-sky-blue">Resources</h5>
                    <ul class="list-unstyled">
                        <li><a href="documentation/README.md" class="text-light text-decoration-none"><i class="fas fa-book me-1"></i> Documentation</a></li>
                        <li><a href="documentation/DEMO_GUIDE.md" class="text-light text-decoration-none"><i class="fas fa-graduation-cap me-1"></i> Demo Guide</a></li>
                    </ul>
                </div>
            </div>
            <hr class="bg-sky-blue opacity-50">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="text-light">&copy; 2025 NeuroNotes. All rights reserved. <span class="text-sky-blue">Powered by Technology</span></p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body {
            background: #0a0a14;
            color: #e0e0ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-brand {
            font-size: 1.8rem;
        }
        
        .display-2 {
            font-weight: 800;
            letter-spacing: -1px;
            text-shadow: 0 0 10px rgba(64, 128, 255, 0.3);
        }
        
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border: 1px solid rgba(100, 100, 255, 0.1);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(64, 128, 255, 0.2);
            border: 1px solid rgba(64, 128, 255, 0.3);
        }
        
        .stat-card {
            background: rgba(30, 30, 60, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(100, 100, 255, 0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: scale(1.05);
            border: 1px solid rgba(64, 128, 255, 0.3);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #4e54c8, #8f94fb);
            border: none;
            box-shadow: 0 5px 15px rgba(78, 84, 200, 0.4);
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #3a40b0, #7a80f0);
            box-shadow: 0 8px 20px rgba(78, 84, 200, 0.6);
        }
        
        .floating-animation {
            position: relative;
            height: 300px;
            width: 300px;
            margin: 0 auto;
        }
        
        .cube {
            position: absolute;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, rgba(78, 84, 200, 0.7), rgba(143, 148, 251, 0.7));
            border-radius: 10px;
            animation: float 6s ease-in-out infinite;
            box-shadow: 0 0 20px rgba(78, 84, 200, 0.5);
        }
        
        .cube-1 {
            top: 20%;
            left: 20%;
            animation-delay: 0s;
        }
        
        .cube-2 {
            top: 50%;
            left: 50%;
            animation-delay: 2s;
        }
        
        .cube-3 {
            top: 30%;
            left: 70%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
            100% {
                transform: translateY(0) rotate(360deg);
            }
        }
        
        .feature-icon {
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .card {
            background: rgba(20, 20, 40, 0.7);
            backdrop-filter: blur(10px);
        }
        
        .card-header {
            background: linear-gradient(45deg, #4e54c8, #8f94fb);
            border: none;
        }
    </style>
</body>
</html>