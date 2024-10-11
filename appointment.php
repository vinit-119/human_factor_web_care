<?php include 'navigation.php'; ?>

<?php
// appointment.php
// Database connection settings
$servername = 'localhost:3308';
$username = 'root'; // Use your database username
$password = ''; // Use your database password
$dbname = 'web_care';

// Start session
session_start();

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

// Fetch all therapists for appointment selection
$therapistsQuery = "SELECT therapist_id, specialty FROM therapists ORDER BY specialty";
$therapistsResult = $conn->query($therapistsQuery);

// Handle the form submission to book an appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $therapist_id = $_POST['therapist'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    $query = "INSERT INTO appointments (patient_id, therapist_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiss', $user_id, $therapist_id, $appointment_date, $appointment_time);

    if ($stmt->execute()) {
        echo '<script>alert("Appointment booked successfully!");</script>';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <style>
        /* Appointment Booking CSS */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        .appointment-booking {
            max-width: 600px;
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

        .appointment-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin: 20px auto;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .book-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .book-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="appointment-booking">
        <h1>Book an Appointment</h1>
        <form action="appointment.php" method="POST" class="appointment-form">
            <div class="form-group">
                <label for="therapist">Select Therapist</label>
                <select id="therapist" name="therapist" required>
                    <?php while ($therapist = $therapistsResult->fetch_assoc()): ?>
                        <option value="<?php echo $therapist['therapist_id']; ?>">
                            <?php echo htmlspecialchars($therapist['specialty']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="appointment_date">Appointment Date</label>
                <input type="date" id="appointment_date" name="appointment_date" required>
            </div>
            <div class="form-group">
                <label for="appointment_time">Appointment Time</label>
                <input type="time" id="appointment_time" name="appointment_time" required>
            </div>
            <button type="submit" class="book-btn">Book Appointment</button>
        </form>
    </div>
</body>
</html>
