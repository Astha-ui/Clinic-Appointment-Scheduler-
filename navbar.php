<?php
// Start session if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar">
    <div class="nav-container">
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="service.php">Services</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>

        <div class="auth-buttons">
            <?php if (isset($_SESSION['username'])): ?>
                <a href="profile.php" class="nav-btn profile-btn">Profile</a>
                <a href="logout.php" class="nav-btn logout-btn" style="color:red;">Logout</a>
            <?php else: ?>
                <a href="login.php" class="nav-btn login-btn">Log In</a>
                <a href="signup.php" class="nav-btn signup-btn">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
