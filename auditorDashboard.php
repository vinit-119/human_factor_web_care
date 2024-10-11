<?php include 'navigation.php'; ?>

<?php
// auditorDashboard.php
// Database connection settings
$servername = 'localhost:3308';
$username = 'root'; // Use your database username
$password = ''; // Use your database password
$dbname = 'web_care';

// Start session
session_start();

// Check if the user is logged in and is an auditor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'auditor') {
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

// Fetch data about patient treatment and therapist activities for auditors
$therapistDataQuery = "SELECT therapist_id, COUNT(appointment_id) AS total_patients, AVG(TIMESTAMPDIFF(MINUTE, appointment_time, NOW())) AS avg_session_duration FROM appointments GROUP BY therapist_id";
$therapistDataResult = $conn->query($therapistDataQuery);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditor Dashboard</title>
    <style>
        /* Auditor Dashboard CSS */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        .auditor-dashboard {
            max-width: 1000px;
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

        .therapist-item {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="auditor-dashboard">
        <h1>Welcome, Auditor <?php echo htmlspecialchars($first_name); ?>!</h1>
        <h2>Therapist Activity Overview</h2>
        <?php if ($therapistDataResult->num_rows > 0): ?>
            <?php while ($therapistData = $therapistDataResult->fetch_assoc()): ?>
                <div class="therapist-item">
                    <p>Therapist ID: <?php echo htmlspecialchars($therapistData['therapist_id']); ?></p>
                    <p>Total Patients Treated: <?php echo htmlspecialchars($therapistData['total_patients']); ?></p>
                    <p>Average Session Duration: <?php echo htmlspecialchars($therapistData['avg_session_duration']); ?> minutes</p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No therapist data available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
