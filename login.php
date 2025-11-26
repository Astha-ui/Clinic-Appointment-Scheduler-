<?php
session_start(); // start the session

// Database connection
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username']; // store username in session
            header("Location: profile.php"); // redirect to profile page
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Email not registered!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | Serenity Therapy</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <header class="login-header">
            <h1>Log in for Serenity Therapy</h1>
            <p>Log in or <a href="signup.html">sign up to join us</a></p>
        </header>
        
        <form action="login.php" method="POST" class="login-form">

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
            <?php if(isset($error)) { echo "<p style='color:red; margin-top:10px;'>$error</p>"; } ?>


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