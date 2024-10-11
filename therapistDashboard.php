<?php
// therapistDashboard.php
include("navigation.php");
// Database connection settings
$servername = 'localhost:3308';
$username = 'root'; // Use your database username
$password = ''; // Use your database password
$dbname = 'web_care';

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is a therapist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'therapist') {
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



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapist Dashboard</title>
    <style>
        /* Therapist Dashboard CSS */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        .therapist-dashboard {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2 {
            text-align: center;
            color: #4CAF50;
        }

        .appointment-item,
        .edit-profile-section {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .edit-profile-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .edit-profile-btn:hover {
            background-color: #45a049;
        }

        /* Patient Profile Section */
        .patient-profile {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-image {
            margin-right: 30px;
        }

        .profile-image img {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-details {
            max-width: 400px;
        }

        .profile-details h2 {
            color: #4CAF50;
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        .profile-details p {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <div class="therapist-dashboard">
        <h1>Welcome to your Dashboard, Dr. <?php echo htmlspecialchars($first_name); ?>!</h1>
      
        <!-- Therapist Profile Update Section -->
        <div class="edit-profile-section">
            <h2>Manage Your Profile</h2>
            <button onclick="location.href='therapistProfile.php'" class="edit-profile-btn">Edit Profile</button>
        </div>

        <!-- Appointments Section -->
        <div class="appointment-list">
            <h2>Your Appointments</h2>
            <?php if ($appointmentsResult->num_rows > 0): ?>
                <?php while ($appointment = $appointmentsResult->fetch_assoc()): ?>
                    <div class="appointment-item">
                        <p>Patient: <?php echo htmlspecialchars($appointment['patient_name']); ?></p>
                        <p>Date: <?php echo htmlspecialchars($appointment['appointment_date']); ?> | Time:
                            <?php echo htmlspecialchars($appointment['appointment_time']); ?>
                        </p>
                        <p>Status: <?php echo htmlspecialchars($appointment['status']); ?></p>
                        <button class="edit-profile-btn">Accept</button>
                        <button class="edit-profile-btn">Decline</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No appointments scheduled.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>