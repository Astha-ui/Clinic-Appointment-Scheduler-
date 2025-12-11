<?php
session_start();

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
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email']; // optional
            header("Location: profile.php"); // redirect after login
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
    <title>Log In</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="login-container">
        <h1>Log in</h1>
        <form method="POST" action="login.php">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <button type="submit">Log In</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>
</body>
</html>
