<?php
// adminDashboard.php
include("navigation.php");
// Database connection settings
$servername = 'localhost:3308';
$username = 'root'; // Use your database username
$password = ''; // Use your database password
$dbname = 'web_care';

// Start session
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Get user session data
$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch all users and system logs from the database
$usersQuery = "SELECT user_id, first_name, last_name, email, role FROM users ORDER BY role, first_name";
$logsQuery = "SELECT action_type, description, timestamp FROM system_logs ORDER BY timestamp DESC";

$usersResult = $conn->query($usersQuery);
$logsResult = $conn->query($logsQuery);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Admin Dashboard CSS */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        .admin-dashboard {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #4CAF50;
        }

        .user-list, .system-logs {
            margin-top: 30px;
        }

        .user-item, .log-item {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .manage-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .manage-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <h1>Welcome to Admin Dashboard, <?php echo htmlspecialchars($first_name); ?>!</h1>

        <!-- User Management Section -->
        <section class="user-list">
            <h2>User Management</h2>
            <?php if ($usersResult->num_rows > 0): ?>
                <?php while ($user = $usersResult->fetch_assoc()): ?>
                    <div class="user-item">
                        <p>Name: <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                        <p>Role: <?php echo htmlspecialchars($user['role']); ?></p>
                        <button class="manage-btn">Manage User</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>
        </section>

        <!-- System Logs Section -->
        <section class="system-logs">
            <h2>System Logs</h2>
            <?php if ($logsResult->num_rows > 0): ?>
                <?php while ($log = $logsResult->fetch_assoc()): ?>
                    <div class="log-item">
                        <p>Action: <?php echo htmlspecialchars($log['action_type']); ?></p>
                        <p>Description: <?php echo htmlspecialchars($log['description']); ?></p>
                        <p>Timestamp: <?php echo htmlspecialchars($log['timestamp']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No logs found.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
