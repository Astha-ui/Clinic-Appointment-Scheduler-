<?php
session_start();

// Only allow logged-in users with role 'user'
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

// Connect to DB
$conn = new mysqli("localhost", "root", "", "clinic_db");
if ($conn->connect_error) die("DB connection failed: " . $conn->connect_error);

// ===== HANDLE DELETE REQUEST =====
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id=? AND email=?");
    $stmt->bind_param("is", $delete_id, $_SESSION['email']);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}

// ===== HANDLE EDIT REQUEST =====
$editSuccess = "";
if (isset($_POST['edit_id'])) {
    $edit_id = intval($_POST['edit_id']);
    $newDate = $_POST['date'];
    $newTime = $_POST['time'];
    $newTreatment = $_POST['treatment'];

    $stmt = $conn->prepare("UPDATE appointments SET date=?, time=?, treatment=? WHERE id=? AND email=?");
    $stmt->bind_param("sssis", $newDate, $newTime, $newTreatment, $edit_id, $_SESSION['email']);
    if ($stmt->execute()) {
        $editSuccess = "Appointment updated successfully!";
    }
    $stmt->close();
}

// Fetch user's appointments
$email = $_SESSION['email'];
$result = $conn->query("SELECT * FROM appointments WHERE email='$email' ORDER BY date DESC");
$appointments = $result->fetch_all(MYSQLI_ASSOC);

include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard | Serenity Therapy</title>
<link rel="stylesheet" href="navbar.css">
<link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard-container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Here's a quick overview of your appointments.</p>

    <?php if ($editSuccess) echo "<p class='edit-success'>$editSuccess</p>"; ?>

    <h2>Your Appointments</h2>

    <?php if (count($appointments) === 0): ?>
        <p class="no-appointments">You have no appointments booked yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Treatment</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($appointments as $appt): ?>
                <tr>
                    <td><?php echo htmlspecialchars($appt['treatment']); ?></td>
                    <td><?php echo $appt['date']; ?></td>
                    <td><?php echo $appt['time']; ?></td>
                    <td>
                        <!-- Edit Form -->
                        <form method="POST" class="edit-form" style="display:inline-block;">
                            <input type="hidden" name="edit_id" value="<?php echo $appt['id']; ?>">
                            <input type="date" name="date" value="<?php echo $appt['date']; ?>" required>
                            <input 
                                type="time" 
                                name="time" 
                                value="<?php echo date('H:i', strtotime($appt['time'])); ?>" 
                                min="09:00" 
                                max="17:00" 
                                step="3600" 
                                required
                            >

                            <select name="treatment" required>
                                <option <?php if($appt['treatment']=='Cognitive Behavioral Therapy') echo 'selected'; ?>>Cognitive Behavioral Therapy</option>
                                <option <?php if($appt['treatment']=='Stress Management') echo 'selected'; ?>>Stress Management</option>
                                <option <?php if($appt['treatment']=='Anxiety Counseling') echo 'selected'; ?>>Anxiety Counseling</option>
                                <option <?php if($appt['treatment']=='Depression Treatment') echo 'selected'; ?>>Depression Treatment</option>
                                <option <?php if($appt['treatment']=='Family Therapy') echo 'selected'; ?>>Family Therapy</option>
                                <option <?php if($appt['treatment']=='Grief Counseling') echo 'selected'; ?>>Grief Counseling</option>
                                <option <?php if($appt['treatment']=='Work-Life Balance') echo 'selected'; ?>>Work-Life Balance</option>
                                <option <?php if($appt['treatment']=='Mindfulness Therapy') echo 'selected'; ?>>Mindfulness Therapy</option>
                            </select>
                            <button type="submit" class="btn">Update</button>
                        </form>

                        <!-- Delete -->
                        <a href="?delete_id=<?php echo $appt['id']; ?>" onclick="return confirm('Are you sure?');" class="btn del">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="appointment.php" class="btn">Book New Appointment</a>
</div>

</body>
</html>
