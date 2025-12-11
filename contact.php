<?php
session_start();
include 'navbar.php';

// ======== ENABLE ERROR REPORTING ========
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$success = "";
$error = "";

// ======== CONNECT TO DATABASE ========
$conn = new mysqli("localhost", "root", "", "clinic_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ======== HANDLE FORM SUBMISSION ========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Optionally, force only logged-in users to submit
    if (!isset($_SESSION['username'])) {
        $error = "You must be logged in to send a message.";
    } else {

        // Get and sanitize input
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $message = trim($_POST['message']);

        // ======== VALIDATION ========
        if (empty($name) || empty($email) || empty($message)) {
            $error = "Name, email, and message are required!";
        } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            $error = "Name can contain only letters and spaces!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format!";
        } elseif (!empty($phone) && !preg_match("/^[0-9+\-\s]+$/", $phone)) {
            $error = "Phone number can only contain numbers, spaces, +, and -!";
        } else {
            // ======== INSERT DATA USING PREPARED STATEMENT ========
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                $error = "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            } else {
                $stmt->bind_param("ssss", $name, $email, $phone, $message);
                if ($stmt->execute()) {
                    $success = "Thank you! Your message has been sent successfully.";
                } else {
                    $error = "Error inserting data: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    // }
}
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medicare Clinic | Contact</title>
<link rel="stylesheet" href="contact.css">
<link rel="stylesheet" href="navbar.css">
<link rel="stylesheet" href="footer.css">
</head>
<body>

<div class="contact-container">
    <header>
        <h1>Contact Us</h1>
        <p class="intro-text">Have questions? We're here to help. Reach out to us and we'll get back to you as soon as possible.</p>
    </header>

    <?php
    if ($success) echo "<p style='color:green;font-weight:bold;margin-bottom:10px;'>$success</p>";
    if ($error) echo "<p style='color:red;font-weight:bold;margin-bottom:10px;'>$error</p>";
    ?>

    <main>
        <section class="contact-info">
            <h2>Get in Touch</h2>
            <div class="contact-details">
                <div class="contact-item">
                    <h3>Address</h3>
                    <p>Tripura Marg<br>Near angan sweets<br>Kathmandu, State 44600</p>
                </div>
                <div class="contact-item">
                    <h3>Phone</h3>
                    <p>(+977) 9700330474</p>
                </div>
                <div class="contact-item">
                    <h3>Email</h3>
                    <p>info@serenitytherapy.com<br>appointments@serenitytherapy.com</p>
                </div>
                <div class="contact-item">
                    <h3>Hours</h3>
                    <p>Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 3:00 PM<br>Sunday: Closed</p>
                </div>
            </div>
        </section>

        <hr>

        <section class="contact-form">
            <form action="contact.php" method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" placeholder="Your full name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="(+977) 9700330474">
                </div>

                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" placeholder="How can we help you?" required></textarea>
                </div>

                <button type="submit">Send Message</button>
            </form>
        </section>
    </main>
</div>

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-about">
                <h3>Serenity Therapy Clinic</h3>
                <p>Your trusted partner in mental and emotional wellness.<br>
                    We're here to support you on your journey to better mental health.</p>
            </div>

            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="appointment.php">Book Appointment</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h4>Contact Info</h4>
                <ul>
                    <li>📍 Tripureshwor, Kathmandu</li>
                    <li>📞 (+977) 9700330474</li>
                    <li>💬 info@serenitytherapy.com</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2024 Serenity Therapy Clinic. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
function validateForm() {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();
    const phone = document.getElementById('phone').value.trim();

    if (!name || !email || !message) {
        alert("Name, email, and message are required!");
        return false;
    }
    if (!/^[a-zA-Z ]+$/.test(name)) {
        alert("Name can contain only letters and spaces!");
        return false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("Invalid email format!");
        return false;
    }
    if (phone && !/^[0-9+\-\s]+$/.test(phone)) {
        alert("Phone number can contain only numbers, spaces, +, and -");
        return false;
    }
    return true;
}
</script>

</body>
</html>