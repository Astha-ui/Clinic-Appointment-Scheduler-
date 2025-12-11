<?php
session_start();

// Only allow admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}

// DB connection
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ======== HANDLE DELETION ========
if (isset($_GET['delete'])) {
    $type = $_GET['type'] ?? '';
    $id = intval($_GET['id'] ?? 0);

    if ($type === 'appointment') {
        $conn->query("DELETE FROM appointments WHERE id=$id");
    } elseif ($type === 'patient') {
        $patient_res = $conn->query("SELECT email FROM users WHERE id=$id");
        if ($patient_res->num_rows > 0) {
            $email = $patient_res->fetch_assoc()['email'];
            $conn->query("DELETE FROM appointments WHERE email='$email'");
            $conn->query("DELETE FROM users WHERE id=$id");
        }
    } elseif ($type === 'message') {
        $conn->query("DELETE FROM contact_messages WHERE id=$id");
    }
    header("Location: admin.php"); // refresh page
    exit();
}

// ======== HANDLE APPOINTMENT EDIT ========
if (isset($_POST['edit_appointment'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $treatment = $conn->real_escape_string($_POST['treatment']);
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);

    $conn->query("UPDATE appointments SET name='$name', email='$email', treatment='$treatment', date='$date', time='$time' WHERE id=$id");
    header("Location: admin.php");
    exit();
}

// Fetch tables
$appointments_result = $conn->query("SELECT * FROM appointments ORDER BY date ASC, time ASC");
$patients_result = $conn->query("
    SELECT u.id, u.username, u.email, COUNT(a.id) AS total_appointments, MAX(a.date) AS last_appointment
    FROM users u
    LEFT JOIN appointments a ON u.email = a.email
    GROUP BY u.id
");
$messages_result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Serenity Therapy Clinic</title>
<style>
body {font-family: Arial, sans-serif; margin:0; background:#f7f7f7;}
.sidebar {width:220px; background:#38a186; min-height:100vh; position:fixed; top:0; left:0; color:#fff; padding-top:20px;}
.sidebar .logo {font-size:20px; font-weight:bold; text-align:center; margin-bottom:20px;}
.sidebar nav a {display:block; color:#fff; text-decoration:none; padding:10px 20px; margin:2px 0;}
.sidebar nav a.active-link, .sidebar nav a:hover {background:#2f836d;}
.main-content {margin-left:220px; padding:20px;}
header {display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;}
header .profile img {border-radius:50%; width:40px; height:40px;}
header .logout-btn {margin-left:10px; text-decoration:none; padding:6px 12px; background:#e74c3c; color:#fff; border-radius:4px;}
header .logout-btn:hover {background:#c0392b;}
table {width:100%; border-collapse:collapse; margin-top:10px;}
table th, table td {padding:10px; border:1px solid #ddd;}
table th {background:#38a186; color:#fff;}
table tr:nth-child(even){background:#f2f2f2;}
.btn {padding:5px 10px; border:none; border-radius:4px; text-decoration:none; color:#fff; cursor:pointer;}
.delete-btn {background:#e74c3c;} .delete-btn:hover {background:#c0392b;}
.edit-btn {background:#3498db;} .edit-btn:hover {background:#2980b9;}
.hidden {display:none;}
.edit-form input, .edit-form select {padding:5px; margin:2px;}
</style>
</head>
<body>

<aside class="sidebar">
    <div class="logo">Serenity Therapy</div>
    <nav>
        <a href="#" data-view="appointments" class="active-link">Appointments</a>
        <a href="#" data-view="patients">Patients</a>
        <a href="#" data-view="messages">Messages</a>
    </nav>
</aside>

<main class="main-content">
<header>
    <h1 id="main-title">Appointments</h1>
    <div class="profile">
        <img src="https://placehold.co/40x40/3b82f6/ffffff?text=ST" alt="Admin Avatar">
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<!-- Appointments -->
<section id="appointments-view" class="view">
    <h2>All Appointments</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th><th>Email</th><th>Treatment</th><th>Date</th><th>Time</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if($appointments_result->num_rows > 0): ?>
            <?php while($row = $appointments_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['treatment']) ?></td>
                <td><?= $row['date'] ?></td>
                <td><?= $row['time'] ?></td>
                <td>
                    <form method="POST" class="edit-form" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
                        <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
                        <input type="text" name="treatment" value="<?= htmlspecialchars($row['treatment']) ?>" required>
                        <input type="date" name="date" value="<?= $row['date'] ?>" required>
                        <select name="time" required>
                            <?php 
                            $times = ["09:00 am","10:00 am","11:00 am","12:00 pm","01:00 pm","02:00 pm","03:00 pm","04:00 pm","05:00 pm"];
                            foreach($times as $t){
                                $selected = $t==$row['time']?"selected":"";
                                echo "<option value='$t' $selected>$t</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="edit_appointment" class="btn edit-btn">Update</button>
                    </form>
                    <a href="admin.php?delete=appointment&id=<?= $row['id'] ?>" onclick="return confirm('Delete this appointment?');" class="btn delete-btn">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr><td colspan="6">No appointments found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- Patients -->
<section id="patients-view" class="view hidden">
    <h2>All Patients</h2>
    <table>
        <thead>
            <tr><th>ID</th><th>Username</th><th>Email</th><th>Last Appointment</th><th>Total Appointments</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php if($patients_result->num_rows > 0): ?>
            <?php while($row = $patients_result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['last_appointment'] ?? 'N/A' ?></td>
                <td><?= $row['total_appointments'] ?></td>
                <td>
                    <a href="admin.php?delete=patient&id=<?= $row['id'] ?>" onclick="return confirm('Delete this patient and all appointments?');" class="btn delete-btn">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr><td colspan="6">No patients found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- Messages -->
<section id="messages-view" class="view hidden">
    <h2>Patient Messages</h2>
    <table>
        <thead>
            <tr><th>Name</th><th>Email</th><th>Phone</th><th>Message</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php if($messages_result->num_rows > 0): ?>
            <?php while($row = $messages_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td>
                    <a href="admin.php?delete=message&id=<?= $row['id'] ?>" onclick="return confirm('Delete this message?');" class="btn delete-btn">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr><td colspan="5">No messages found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
</main>

<script>
const views = document.querySelectorAll('.view');
const navLinks = document.querySelectorAll('.sidebar nav a');
const mainTitle = document.getElementById('main-title');

navLinks.forEach(link=>{
    link.addEventListener('click', e=>{
        e.preventDefault();
        const target = e.target.dataset.view;
        views.forEach(v=>v.classList.add('hidden'));
        document.getElementById(target+'-view').classList.remove('hidden');
        navLinks.forEach(l=>l.classList.remove('active-link'));
        e.target.classList.add('active-link');
        mainTitle.textContent = target.charAt(0).toUpperCase() + target.slice(1);
    });
});
</script>
</body>
</html>
