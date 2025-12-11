<?php
// Start session if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine user role
$role = $_SESSION['role'] ?? 'guest';
$isAdmin = $role === 'admin';
$isUser = $role === 'user';
?>
<link rel="stylesheet" href="navbar.css">

<nav class="navbar">
    <div class="nav-container">
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="appointment.php">Appointments</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if ($isAdmin): ?>
                <li><a href="admin.php">Admin Dashboard</a></li>
            <?php elseif ($isUser): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
            <?php endif; ?>
        </ul>

        <div class="auth-buttons">
            <?php if (isset($_SESSION['username'])): ?>
                <a href="profile.php" class="nav-btn profile-btn">Profile</a>
                <a href="logout.php" class="nav-btn logout-btn">Logout</a>
            <?php else: ?>
                <a href="login.php" class="nav-btn login-btn">Log In</a>
                <a href="signup.php" class="nav-btn signup-btn">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
