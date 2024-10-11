<?php
include("navigation.php");

// Check if the session is already active, if not, start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection settings
$servername = 'localhost:3308';
$username = 'root'; // Use your database username
$password = ''; // Use your database password
$dbname = 'web_care';

// Check if the user is logged in and is a therapist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'therapist') {
    header('Location: login.php');
    exit();
}

$therapist_id = $_SESSION['user_id'];
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch all patients registered with the therapist
// Fetch all patients registered with the therapist, using JOIN to get names from the webuser table
$patientsQuery = "
    SELECT patient.patient_id, webuser.first_name, webuser.last_name, patient.profile_picture 
    FROM patient 
    JOIN webuser ON patient.user_id = webuser.user_id 
    WHERE patient.therapist_id = ?";
$patientsStmt = $conn->prepare($patientsQuery);
$patientsStmt->bind_param('i', $therapist_id);
$patientsStmt->execute();
$patientsResult = $patientsStmt->get_result();


// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapist Group Session</title>
    <style>
        .group-session-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .patient-list, .group-session-list {
            width: 45%;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            min-height: 200px;
        }

        .patient-list h3, .group-session-list h3 {
            text-align: center;
            color: #4CAF50;
        }

        .draggable-item {
            padding: 10px;
            margin: 5px 0;
            background-color: #4CAF50;
            color: white;
            cursor: move;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .draggable-item.dragging {
            background-color: #66BB6A;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <h1>Create and Manage Group Sessions</h1>

    <!-- Form to Create a New Group Session -->
    <form action="createGroupSession.php" method="POST">
        <label for="session_name">New Group Session Name:</label>
        <input type="text" id="session_name" name="session_name" required>
        <button type="submit">Create Group Session</button>
    </form>

    <div class="group-session-container">
        <!-- List of All Patients Assigned to the Therapist -->
        <div class="patient-list" id="patient-list">
            <h3>Available Patients</h3>
            <?php while ($patient = $patientsResult->fetch_assoc()): ?>
                <div class="draggable-item" draggable="true" data-id="<?php echo $patient['patient_id']; ?>">
                    <img src="<?php echo htmlspecialchars($patient['profile_picture']); ?>" alt="Profile Picture" width="30" height="30">
                    <?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Group Session Area -->
        <div class="group-session-list" id="group-session-list">
            <h3>Group Session Members</h3>
            <!-- This area will be populated with patients added to the session via drag-and-drop -->
        </div>
    </div>

    <script>
        const draggables = document.querySelectorAll('.draggable-item');
        const patientList = document.getElementById('patient-list');
        const sessionList = document.getElementById('group-session-list');

        draggables.forEach(draggable => {
            draggable.addEventListener('dragstart', () => {
                draggable.classList.add('dragging');
            });

            draggable.addEventListener('dragend', () => {
                draggable.classList.remove('dragging');
            });
        });

        sessionList.addEventListener('dragover', (e) => {
            e.preventDefault();
            const draggingItem = document.querySelector('.dragging');
            sessionList.appendChild(draggingItem);
        });

        patientList.addEventListener('dragover', (e) => {
            e.preventDefault();
            const draggingItem = document.querySelector('.dragging');
            patientList.appendChild(draggingItem);
        });
    </script>
</body>
</html>
