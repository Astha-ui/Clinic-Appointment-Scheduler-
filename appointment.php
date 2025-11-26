<?php
// Start session
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'clinic_db'); // Use your DB name

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $treatment = $conn->real_escape_string($_POST['treatment']);
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);

    if(empty($name) || empty($email) || empty($treatment) || empty($date) || empty($time)) {
        $error = "All fields are required!";
    } else {
        // Insert appointment into database
        $stmt = $conn->prepare("INSERT INTO appointments (name, email, treatment, date, time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $treatment, $date, $time);

        if($stmt->execute()) {
            $success = "Appointment booked successfully for $date at $time!";
        } else {
            $error = "Error booking appointment. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Make an Appointment | Serenity Therapy</title>
<link rel="stylesheet" href="appointment.css" />
<link rel="stylesheet" href="navbar.css">
<link rel="stylesheet" href="footer.css">
<style>
/* Add only necessary form adjustments */
.submit-btn {
    background-color: #38a186;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px;
}
.submit-btn:hover {
    background-color: #2f836d;
}
.selected-date {
    background-color: #38a186;
    color: white;
    border-radius: 50%;
}
.time-btn.selected {
    background-color: #38a186;
    color: white;
}
.time-btn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}
#successMsg {
    font-weight: bold;
}
</style>
</head>

<body>
<nav class="navbar">
    <div class="nav-container">
        <ul class="nav-links">
            <li><a href="home.html">Home</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="service.html">Services</a></li>
            <li><a href="contact.html">Contact</a></li>
        </ul>
        <div class="auth-buttons">
            <a href="login.html" class="nav-btn login-btn">Log In</a>
            <a href="signup.html" class="nav-btn signup-btn">Sign Up</a>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="title">Make an Appointment</h1>

    <div class="appointment-box">
        <!-- LEFT SECTION -->
        <div class="left-section">
            <label class="section-label">Select Date</label>
            <div class="calendar">
                <div class="calendar-header">
                    <button type="button" class="nav-btn" id="prevMonth">&#10094;</button>
                    <span class="month" id="monthDisplay"></span>
                    <button type="button" class="nav-btn" id="nextMonth">&#10095;</button>
                </div>
                <div class="calendar-grid" id="calendarGrid"></div>
            </div>
        </div>

        <!-- RIGHT SECTION -->
        <div class="right-section">
            <form id="appointmentForm" method="POST">
                <label class="section-label">Enter your name</label>
                <input type="text" placeholder="Your full name" class="email-input" id="name" name="name" />

                <label class="section-label">Enter your email</label>
                <input type="email" placeholder="example@mail.com" class="email-input" id="email" name="email" />

                <label class="section-label">Select Treatment</label>
                <select class="email-input" id="treatment" name="treatment">
                    <option value="">-- Select Treatment --</option>
                    <option>Cognitive Behavioral Therapy</option>
                    <option>Stress Management</option>
                    <option>Anxiety Counseling</option>
                    <option>Depression Treatment</option>
                    <option>Family Therapy</option>
                    <option>Grief Counseling</option>
                    <option>Work-Life Balance</option>
                    <option>Mindfulness Therapy</option>
                </select>

                <label class="section-label">Select Time</label>
                <div class="time-grid">
                    <button type="button" class="time-btn">09:00 am</button>
                    <button type="button" class="time-btn">10:00 am</button>
                    <button type="button" class="time-btn">11:00 am</button>
                    <button type="button" class="time-btn">12:00 pm</button>
                    <button type="button" class="time-btn">01:00 pm</button>
                    <button type="button" class="time-btn">02:00 pm</button>
                    <button type="button" class="time-btn">03:00 pm</button>
                    <button type="button" class="time-btn">04:00 pm</button>
                    <button type="button" class="time-btn">05:00 pm</button>
                </div>

                <!-- Hidden inputs for date and time -->
                <input type="hidden" name="date" id="selectedDate">
                <input type="hidden" name="time" id="selectedTime">

                <button type="submit" class="submit-btn">Get Appointment</button>

                <!-- Messages from PHP -->
                <?php
                if(isset($success)) echo "<p style='color:green;margin-top:10px;'>$success</p>";
                if(isset($error)) echo "<p style='color:red;margin-top:10px;'>$error</p>";
                ?>
            </form>
        </div>
    </div>
</div>

<script>
let currentDate = new Date();
const monthDisplay = document.getElementById("monthDisplay");
const calendarGrid = document.getElementById("calendarGrid");

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    monthDisplay.textContent = currentDate.toLocaleString("default", {month: "long", year: "numeric"});

    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    calendarGrid.innerHTML = "";
    for (let i = 0; i < firstDay; i++) calendarGrid.innerHTML += `<span class="empty"></span>`;
    for (let day = 1; day <= lastDate; day++) {
        const dateEl = document.createElement("span");
        dateEl.textContent = day;
        dateEl.addEventListener("click", () => selectDate(day));
        calendarGrid.appendChild(dateEl);
    }
}

function selectDate(day) {
    const today = new Date();
    const clicked = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);

    if (clicked < new Date(today.getFullYear(), today.getMonth(), today.getDate())) {
        alert("You can't book an appointment for the past.");
        return;
    }

    document.querySelectorAll(".calendar-grid span").forEach(s => s.classList.remove("selected-date"));
    [...calendarGrid.children].find(s => s.textContent == day).classList.add("selected-date");

    // Set hidden input for PHP
    document.getElementById('selectedDate').value = clicked.toISOString().split('T')[0];

    // Disable past times if today
    const timeButtons = document.querySelectorAll(".time-btn");
    if (clicked.toDateString() === today.toDateString()) {
        const currentHour = today.getHours();
        timeButtons.forEach(btn => {
            let btnHour = parseInt(btn.textContent.split(":")[0]);
            if (btn.textContent.includes("pm") && btnHour !== 12) btnHour += 12;
            btn.disabled = btnHour <= currentHour;
        });
    } else {
        timeButtons.forEach(btn => btn.disabled = false);
    }
}

document.getElementById("prevMonth").onclick = () => { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(); };
document.getElementById("nextMonth").onclick = () => { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(); };

// Time selection
document.querySelectorAll(".time-btn").forEach(btn => {
    btn.onclick = () => {
        if (btn.disabled) return;
        document.getElementById('selectedTime').value = btn.textContent;
        document.querySelectorAll(".time-btn").forEach(b => b.classList.remove("selected"));
        btn.classList.add("selected");
    };
});

renderCalendar();
</script>
</body>
</html>
