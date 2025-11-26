<?php
// Start session
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'clinic_db'); // replace 'clinic_db' with your DB name

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        // Insert into database
        $conn->query("INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$password')");
        $_SESSION['username'] = $username; // log in user
        header("Location: profile.php"); // redirect to profile
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Serenity Therapy</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="signup-container">
        <header class="signup-header">
            <h1>Sign up for Serenity Therapy</h1>
            <p>Create a free account or <a href="login.html">log in</a></p>
        </header>
        
        <form class="signup-form" method="POST" action="">

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group password-group">
                <label for="password">Password</label>
                <div class="password-input-wrapper">
                    <input type="password" id="password" name="password" required>
                    <span class="show-hide-btn" onclick="togglePasswordVisibility()">Show</span>
                </div>
            </div>
            <?php if(isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>


            <button type="submit" class="sign-up-button">Sign Up</button>
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