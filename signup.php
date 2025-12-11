<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Server-side validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        // Escape email and username for SQL
        $email = $conn->real_escape_string($email);
        $username = $conn->real_escape_string($username);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Check if email exists
        $check = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            $conn->query("INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$passwordHash')");
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="signup-container">
        <div class="signup-header">
            <h1>Sign Up</h1>
            <p>Create your account below.</p>
        </div>

        <form class="signup-form" method="POST" action="signup.php" onsubmit="return validateForm()">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="password-input-wrapper">
                    <input type="password" name="password" id="password" required>
                    <span class="show-hide-btn" onclick="togglePassword()">Show</span>
                </div>
            </div>

            <?php if(isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

            <button class="sign-up-button" type="submit">Sign Up</button>
        </form>

        <p class="login-text">Already have an account? <a href="login.php">Log in</a><br/><a href="admin_login.php"> admin</br></p>
    </div>

<script>
function togglePassword() {
    const pwd = document.getElementById("password");
    const btn = document.querySelector(".show-hide-btn");
    if (pwd.type === "password") {
        pwd.type = "text";
        btn.innerText = "Hide";
    } else {
        pwd.type = "password";
        btn.innerText = "Show";
    }
}

// Client-side validation
function validateForm() {
    const email = document.getElementById('email').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(email)) {
        alert("Please enter a valid email.");
        return false;
    }

    if (username.length < 3) {
        alert("Username must be at least 3 characters long.");
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
