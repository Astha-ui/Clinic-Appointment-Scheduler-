<?php
session_start();

// Hardcoded admin credentials
$admin_email = "admin@clinic.com";
$admin_password = "Admin@123"; // change if you want

$error = "";

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true; // mark admin as logged in
        header("Location: admin.php"); // go to admin dashboard
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
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <header class="login-header">
            <h1>Admin Login</h1>
        </header>
        <form action="admin-login.php" method="POST" class="login-form">
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
            <?php if($error !== "") { echo "<p style='color:red; margin-top:10px;'>$error</p>"; } ?>
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
    </script>
</body>
</html>
