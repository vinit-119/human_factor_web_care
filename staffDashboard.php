<?php include 'navigation.php'; ?>

<?php
// staffDashboard.php
// Database connection settings
$servername = 'localhost:3308';
$username = 'root'; // Use your database username
$password = ''; // Use your database password
$dbname = 'web_care';

// Start session
session_start();

// Check if the user is logged in and is professional staff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'professional_staff') {
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

// Fetch demographic data of patients for staff access
$patientsQuery = "SELECT first_name, last_name, age, gender FROM patients JOIN users ON patients.user_id = users.user_id";
$patientsResult = $conn->query($patientsQuery);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Staff Dashboard</title>
    <style>
        /* Staff Dashboard CSS */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        .staff-dashboard {
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

        .patient-item {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="staff-dashboard">
        <h1>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>
        <h2>Patient Demographic Information</h2>
        <?php if ($patientsResult->num_rows > 0): ?>
            <?php while ($patient = $patientsResult->fetch_assoc()): ?>
                <div class="patient-item">
                    <p>Name: <?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?></p>
                    <p>Age: <?php echo htmlspecialchars($patient['age']); ?></p>
                    <p>Gender: <?php echo htmlspecialchars($patient['gender']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No patients found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
