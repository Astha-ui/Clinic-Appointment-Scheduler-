<?php
session_start();

// Hardcoded admin credentials
$admin_email = "admin@clinic.com";
$admin_password = "Admin@123"; // change if needed

$error = "";

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Server-side validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } elseif ($email === $admin_email && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin_login.css">
</head>
<body>

        <?php include 'navbar.php'; ?>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-header">
            <h1>Admin Login</h1>
            <p>Enter your credentials to access the dashboard</p>
        </div>

        <form action="admin_login.php" method="POST" class="login-form" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group password-group">
                <label for="password">Password</label>
                <div class="password-input-wrapper">
                    <input type="password" id="password" name="password" required>
                    <span class="show-hide-btn" onclick="togglePasswordVisibility()">Show</span>
                </div>
            </div>

            <?php if($error !== "") { echo "<p class='error-msg'>$error</p>"; } ?>

            <button type="submit" class="log-in-button">Log In</button>
        </form>
    </div>

<script>
function togglePasswordVisibility() {
    const passwordField = document.getElementById('password');
    const toggleButton = document.querySelector('.show-hide-btn');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleButton.textContent = 'Hide';
    } else {
        passwordField.type = 'password';
        toggleButton.textContent = 'Show';
    }
}

// Client-side validation
function validateForm() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(email)) {
        alert("Please enter a valid email.");
        return false;
    }
    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
    }
    return true;
}
</script>

</body>
</html>
