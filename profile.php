<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Settings</title>
    <link rel="stylesheet" href="profile.css">
</head>

<body>
    <?php include 'navbar.php'; ?> 

    <div class="profile-container">

        <div class="header-bg"></div>

        <div class="content">

            <!-- Avatar Upload -->
            <div class="avatar-wrapper">
                <label for="profile-photo-upload" class="avatar-label" id="avatar-label">

                    <svg id="profile-avatar-svg" class="default-icon" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                            clip-rule="evenodd" />
                    </svg>

                    <img src="" class="hidden-img" id="uploaded-avatar-img">
                </label>

                <input type="file" id="profile-photo-upload" accept="image/*" class="avatar-input">
            </div>

            <!-- Username -->
            <h1 class="username">
                <?php echo $_SESSION['username']; ?>
            </h1>

            <!-- Contact Info -->
            <div id="contact-info" class="contact-info hidden-block">

                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?php echo $_SESSION['email'] ?? 'Not set'; ?></span>
                </div>
            </div>

            <!-- Logout -->
            <a href="profile.php?logout=true" class="logout-btn">
                Logout
            </a>

        </div>
    </div>

<script>
    // Avatar preview
    document.getElementById('profile-photo-upload').addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = event => {
            document.getElementById('profile-avatar-svg').style.display = 'none';

            const img = document.getElementById('uploaded-avatar-img');
            img.src = event.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    // Show info on load
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('contact-info').classList.remove('hidden-block');
    });
</script>

</body>
</html>
