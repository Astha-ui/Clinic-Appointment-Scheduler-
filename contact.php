<?php
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);

    // Your email where messages will be sent
    $to = "your-email@example.com"; // <-- Replace with your email
    $subject = "New Contact Form Submission from $name";

    $body = "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n";
    $body .= "Message:\n$message\n";

    $headers = "From: $email";

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        $success = "Thank you! Your message has been sent successfully.";
    } else {
        $error = "Oops! Something went wrong, please try again.";
    }
}
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
<nav class="navbar">
    <div class="nav-container">
        <ul class="nav-links">
            <li><a href="home.html">Home</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="service.html">Services</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="auth-buttons">
            <a href="login.html" class="nav-btn login-btn">Log In</a>
            <a href="signup.html" class="nav-btn signup-btn">Sign Up</a>
        </div>
    </div>
</nav>

<div class="container">
    <header>
        <h1>Contact Us</h1>
        <p class="intro-text">Have questions? We're here to help. Reach out to us and we'll get back to you as soon as possible.</p>
    </header>

    <?php
    if ($success) {
        echo "<p style='color:green;font-weight:bold;margin-bottom:10px;'>$success</p>";
    }
    if ($error) {
        echo "<p style='color:red;font-weight:bold;margin-bottom:10px;'>$error</p>";
    }
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
            <form action="contact.php" method="POST">
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
                    <li><a href="#">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="service.html">Services</a></li>
                    <li><a href="appointment.html">Book Appointment</a></li>
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
            <div class="legal-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
