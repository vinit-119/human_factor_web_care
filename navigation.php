
<?php
// navigation.php
// Start session to access user data
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$role = $isLoggedIn ? $_SESSION['role'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Navigation Bar CSS */
        .navbar {
            background-color: #4CAF50;
            overflow: hidden;
            padding: 10px 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .navbar a {
            float: left;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 17px;
        }

        .navbar a:hover {
            background-color: #45a049;
            color: white;
        }

        .navbar a.active {
            background-color: #3e8e41;
            color: white;
        }

        .navbar .right {
            float: right;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="home.php" class="active">Home</a>

        <?php if ($isLoggedIn): ?>
            <?php if ($role == 'patient'): ?>
                <a href="patientDashboard.php">Dashboard</a>
                <a href="appointment.php">Book Appointment</a>
                <a href="dailyJournal.php">Daily Journal</a>
            <?php elseif ($role == 'therapist'): ?>
                <a href="therapistDashboard.php">Dashboard</a>
                <a href="patientSummary.php">Patient Summary</a>
                <a href="therapistgroupsession.php">Group Sessions</a>
            <?php elseif ($role == 'admin'): ?>
                <a href="adminDashboard.php">Dashboard</a>
                <a href="manageUsers.php">Manage Users</a>
                <a href="systemLogs.php">System Logs</a>
            <?php elseif ($role == 'professional_staff'): ?>
                <a href="staffDashboard.php">Dashboard</a>
                <a href="scheduleManagement.php">Manage Schedule</a>
            <?php elseif ($role == 'auditor'): ?>
                <a href="auditorDashboard.php">Dashboard</a>
                <a href="reports.php">View Reports</a>
            <?php endif; ?>
            <a href="logout.php" class="right">Logout</a>
        <?php else: ?>
            <a href="login.php" class="right">Login</a>
            <a href="register.php" class="right">Sign Up</a>
        <?php endif; ?>
    </div>
</body>
</html>
