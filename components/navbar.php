<?php
// Navigation bar component
// This file should be included with the necessary path variables
?>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?php echo $dashboardPath ?? 'dashboard.php'; ?>">Online Notes</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>" href="<?php echo $dashboardPath ?? 'dashboard.php'; ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'upload.php') ? 'active' : ''; ?>" href="<?php echo $uploadPath ?? 'notes/upload.php'; ?>">Upload Notes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'mynotes.php') ? 'active' : ''; ?>" href="<?php echo $mynotesPath ?? 'notes/mynotes.php'; ?>">My Notes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'search.php') ? 'active' : ''; ?>" href="<?php echo $searchPath ?? 'notes/search.php'; ?>">Search Notes</a>
                </li>
                <?php if (!empty($showAdmin) && $showAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin.php') ? 'active' : ''; ?>" href="<?php echo $adminPath ?? 'admin/admin.php'; ?>">Admin Panel</a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="navbar-text me-3">Hello, <?php echo escapeHtml($_SESSION['name'] ?? ''); ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $logoutPath ?? 'user/logout.php'; ?>">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>