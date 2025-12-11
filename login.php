<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Server-side validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        // Escape email for SQL
        $email = $conn->real_escape_string($email);
        $result = $conn->query("SELECT * FROM users WHERE email='$email'");

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $email;      
                $_SESSION['role'] = $user['role']; 

                header("Location: home.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "Email not registered!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-wrapper">
    <div class="login-container">

        <div class="login-header">
            <h1>Log In</h1>
            <p>Welcome back! Please enter your credentials.</p>
        </div>

        <form class="login-form" method="POST" action="login.php" onsubmit="return validateForm()">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="password-input-wrapper">
                    <input type="password" name="password" id="pwd" required>
                    <span class="show-hide-btn" onclick="togglePassword()">Show</span>
                </div>
            </div>

            <?php if(isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

            <button class="log-in-button" type="submit">Log In</button>
        </form>

        <p class="signup-text">
            Don't have an account? <a href="signup.php">Sign up<br /><a href="admin_login.php">Admin </a>
        </p>

    </div>
</div>

<script>
function togglePassword() {
    let pwd = document.getElementById("pwd");
    let btn = document.querySelector(".show-hide-btn");
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
    const pwd = document.getElementById('pwd').value;

    if (!email) {
        alert("Email is required!");
        return false;
    }

    // Simple email regex
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email!");
        return false;
    }

    if (pwd.length < 6) {
        alert("Password must be at least 6 characters long!");
        return false;
    }

    return true; // submit form
}
</script>

</body>
</html>
