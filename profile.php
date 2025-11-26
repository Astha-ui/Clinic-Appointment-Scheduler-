<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle logout
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
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles to match the aesthetic */
        .profile-container {
            /* Mimic the subtle, light-colored header background */
            background-color: #f7f9fb;
        }

        .header-bg {
            background-color: #e0d9f4;
            /* Light lavender/purple for the top section */
            height: 150px;
            /* Adjust height for visual balance */
            /* Using slight curve to match image style */
            border-bottom-left-radius: 50% 20px;
            border-bottom-right-radius: 50% 20px;
        }

        .avatar-wrapper {
            /* Position the circle avatar so it overlaps the header and main content */
            margin-top: -75px;
            /* Half the height of the avatar to pull it up */
            position: relative;
            z-index: 10;
        }

        .avatar-input {
            /* Hide the default file input button */
            display: none;
        }

        .avatar-label {
            cursor: pointer;
            display: block;
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #d1d5db; /* Default background color for the icon container */
            display: flex; /* Added for centering the SVG icon */
            align-items: center;
            justify-content: center;
        }

        /* The uploaded image should fill the container */
        .avatar-label img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Style for the SVG icon placeholder */
        .avatar-label .default-icon {
            width: 80%;
            height: 80%;
            color: white; /* Color of the SVG icon */
            /* Ensure the icon itself doesn't hide the overlay on hover */
            pointer-events: none; 
        }

        /* Style for the 'Change Photo' overlay */
        .avatar-label::after {
            content: 'Change Photo';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0); /* Start transparent */
            color: transparent; /* Start text transparent */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s ease; /* Transition for smooth effect */
        }

        /* Hover effect: make the overlay appear */
        .avatar-label:hover::after {
            background-color: rgba(0, 0, 0, 0.4);
            color: white;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            /* Add separator lines */
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row.contact-item:last-of-type {
            border-bottom: none;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    <div class="profile-container max-w-xl mx-auto rounded-xl shadow-lg my-10 overflow-hidden">
        <!-- Header Background -->
        <div class="header-bg w-full"></div>

        <div class="p-6 md:p-8 pt-0 flex flex-col items-center">

            <!-- Profile Picture Uploader Section -->
            <div class="avatar-wrapper flex justify-center">
                <label for="profile-photo-upload" class="avatar-label" id="avatar-label">
                    <!-- SVG Placeholder Icon - VISIBLE BY DEFAULT -->
                    <svg id="profile-avatar-svg" class="default-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                    </svg>
                    <!-- <img> element - HIDDEN BY DEFAULT (no initial src) -->
                    <img src="" alt="Profile Picture" class="hidden" id="uploaded-avatar-img">
                </label>
                <!-- Hidden Input for File Upload -->
                <input type="file" id="profile-photo-upload" accept="image/*" class="avatar-input">
            </div>

            <!-- Profile Name -->
           <h1 class="text-3xl font-bold text-gray-800 mt-4 mb-8"><?php echo $_SESSION['username']; ?></h1>


            <!-- Contact Information (HIDDEN until login is simulated) -->
            <div id="contact-info" class="w-full space-y-2 mb-8 px-4 hidden">
                <div class="info-row contact-item">
                    <span class="text-gray-500 font-medium text-lg">Phone</span>
                    <span class="text-gray-700 font-semibold text-lg"><?php echo $_SESSION['phone'] ?? '+977-9700330474'; ?></span>
                </div>
                <div class="info-row contact-item">
                    <span class="text-gray-500 font-medium text-lg">Mail</span>
                    <span class="text-gray-700 font-semibold text-lg"><?php echo $_SESSION['email']; ?></span>

                </div>
            </div>

            <!-- Log out Button -->
            <div class="w-full bg-white rounded-lg p-4 space-y-1">

                <!-- Log out Button (Red/Prominent) -->
                <a href="profile.php?logout=true" class="info-row hover:bg-red-50 px-2 rounded-lg items-center !border-b-0">

                    <div class="flex items-center space-x-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-lg text-red-500">Log out</span>
                    </div>
                    <!-- Right Arrow -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

        </div>
    </div>

    <script>
        // JavaScript to handle the file input and display the selected image
        document.getElementById('profile-photo-upload').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgElement = document.getElementById('uploaded-avatar-img');
                    const svgElement = document.getElementById('profile-avatar-svg');

                    // 1. Hide the SVG placeholder icon
                    svgElement.classList.add('hidden');
                    
                    // 2. Show and update the <img> element with the uploaded image
                    imgElement.src = e.target.result;
                    imgElement.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // =======================================================
        // LOGIC: Show contact info only after "login"
        // =======================================================
        document.addEventListener('DOMContentLoaded', () => {
            const contactInfoDiv = document.getElementById('contact-info');
            
            // --- SIMULATION START ---
            // In a real application, you would check a server-side session or a token here.
            const isLoggedIn = true; // Assume success for this demonstration
            // --- SIMULATION END ---

            if (isLoggedIn) {
                // Remove the 'hidden' class to display the contact details
                contactInfoDiv.classList.remove('hidden');
            }
        });
    </script>
</body>

</html>