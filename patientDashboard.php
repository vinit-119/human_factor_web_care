<?php
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

// Check if the user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
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




// Fetch mood trends for chart visualization
$moodQuery = "SELECT date, mood FROM daily_journals WHERE patient_id = ? ORDER BY date DESC LIMIT 7";
$moodStmt = $conn->prepare($moodQuery);
$moodStmt->bind_param('i', $user_id);
$moodStmt->execute();
$moodResult = $moodStmt->get_result();
$moodTrends = $moodResult->fetch_all(MYSQLI_ASSOC);

// Fetch goal progress and activity analysis
$activityQuery = "SELECT goal, sleep_hours, exercise, eating_habits, mood, date FROM activity_records WHERE patient_id = ? ORDER BY date DESC LIMIT 7";
$activityStmt = $conn->prepare($activityQuery);
$activityStmt->bind_param('i', $user_id);
$activityStmt->execute();
$activityResult = $activityStmt->get_result();
$activityData = $activityResult->fetch_all(MYSQLI_ASSOC);

// Fetch upcoming appointments
$appointmentsQuery = "SELECT therapist_id, appointment_date, appointment_time FROM appointments WHERE patient_id = ? AND appointment_date >= CURDATE() ORDER BY appointment_date LIMIT 5";
$appointmentsStmt = $conn->prepare($appointmentsQuery);
$appointmentsStmt->bind_param('i', $user_id);
$appointmentsStmt->execute();
$appointmentsResult = $appointmentsStmt->get_result();

// Fetch recent journal entries
$journalQuery = "SELECT date, mood, entry FROM daily_journals WHERE patient_id = ? ORDER BY date DESC LIMIT 3";
$journalStmt = $conn->prepare($journalQuery);
$journalStmt->bind_param('i', $user_id);
$journalStmt->execute();
$journalResult = $journalStmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js for charts -->
    <style>
        /* Patient Dashboard CSS */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        .patient-dashboard {
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

        .chart-container,
        .upcoming-appointments,
        .goal-progress,
        .recent-journals,
        .daily-affirmation {
            margin-top: 20px;
        }

        .chart-container {
            width: 80%;
            margin: 0 auto;
        }

        .appointment-item,
        .journal-item {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .affirmation-box {
            background-color: #e0f7fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 10px 0;
            font-style: italic;
            color: #00796b;
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
    <div class="patient-dashboard">
        <h1>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>

        <!-- Dynamic Patient Profile Section -->
        <section class="patient-profile">
            <div class="profile-image">
                <img src="images/ketu profile pic.png" alt="Patient Profile Picture">
            </div>
            <div class="profile-details">
            <h3><?php echo htmlspecialchars($first_name); ?>  </h3>  
            <a href="patientProfile.php"><button class="submit-btn">View Profile</button>
            </div>
        </section>
        <!-- Mood Trends Chart -->
        <div class="chart-container">
            <h2>Mood Trends Over the Last Week</h2>
            <canvas id="moodChart"></canvas>
        </div>

        <!-- Goal Progress and Activity Analysis -->
        <div class="goal-progress">
            <h2>Your Goal Progress</h2>
            <p>Compare your activities with the goals you've set for yourself.</p>
            <!-- Display goal progress summary here -->
        </div>

        <!-- Upcoming Appointments Section -->
        <div class="upcoming-appointments">
            <h2>Upcoming Appointments</h2>
            <?php if ($appointmentsResult->num_rows > 0): ?>
                <?php while ($appointment = $appointmentsResult->fetch_assoc()): ?>
                    <div class="appointment-item">
                        <p>Date: <?php echo htmlspecialchars($appointment['appointment_date']); ?> | Time:
                            <?php echo htmlspecialchars($appointment['appointment_time']); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No upcoming appointments.</p>
            <?php endif; ?>
        </div>

        <!-- Daily Affirmation Section -->
        <div class="daily-affirmation">
            <h2>Daily Affirmation</h2>
            <div class="affirmation-box">
                "You are stronger than you think, and you are not alone in this journey."
            </div>
        </div>

        <!-- Recent Journal Entries Section -->
        <div class="recent-journals">
            <h2>Recent Journal Entries</h2>
            <?php if ($journalResult->num_rows > 0): ?>
                <?php while ($entry = $journalResult->fetch_assoc()): ?>
                    <div class="journal-item">
                        <h3><?php echo htmlspecialchars($entry['date']); ?> - Mood:
                            <?php echo htmlspecialchars($entry['mood']); ?>
                        </h3>
                        <p><?php echo htmlspecialchars($entry['entry']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No recent journal entries found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript to generate the mood trends chart -->
    <script>
        const ctx = document.getElementById('moodChart').getContext('2d');
        const moodChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?php foreach ($moodTrends as $mood) {
                    echo '"' . $mood['date'] . '",';
                } ?>],
                datasets: [{
                    label: 'Mood Levels',
                    data: [<?php foreach ($moodTrends as $mood) {
                        echo '"' . $mood['mood'] . '",';
                    } ?>],
                    borderColor: '#4CAF50',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>