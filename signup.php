<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        $conn->query("INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$password')");
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email; // optional
        header("Location: profile.php"); // redirect after signup
        exit();
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
        <h1>Sign Up</h1>
        <form method="POST" action="signup.php">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Log in</a></p>
    </div>
</body>
</html>
