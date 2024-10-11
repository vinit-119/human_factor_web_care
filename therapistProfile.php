<?php include 'navigation.php'; ?>

<?php
// therapistProfile.php
// Database connection settings
$servername = 'localhost:3308';
$username = 'root'; // Use your database username
$password = ''; // Use your database password
$dbname = 'web_care';

// Start session
session_start();

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

// Handle the form submission to update therapist profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $specialty = $_POST['specialty'];
    $experience = $_POST['experience'];
    $phone = $_POST['phone'];
    $license_number = $_POST['license_number'];
    $availability = $_POST['availability'];

    // Insert or update therapist details in the database
    $query = "INSERT INTO therapists (user_id, specialty, experience, phone, license_number, availability) 
              VALUES (?, ?, ?, ?, ?, ?) 
              ON DUPLICATE KEY UPDATE specialty = VALUES(specialty), experience = VALUES(experience), 
              phone = VALUES(phone), license_number = VALUES(license_number), availability = VALUES(availability)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('isisss', $user_id, $specialty, $experience, $phone, $license_number, $availability);

    if ($stmt->execute()) {
        // Redirect to the therapist dashboard after successful profile update
        header('Location: therapistDashboard.php');
        exit();
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
    <title>Therapist Profile</title>
    <style>
        /* Therapist Profile CSS */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        .therapist-profile-page {
            max-width: 800px;
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

        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin: 20px auto;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1em;
        }

        .save-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
        }

        .save-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="therapist-profile-page">
        <h1>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>
        <h2>Complete Your Profile</h2>

        <form action="therapistProfile.php" method="POST" class="profile-form">
            <div class="form-group">
                <label for="specialty">Specialty</label>
                <input type="text" id="specialty" name="specialty" required>
            </div>
            <div class="form-group">
                <label for="experience">Experience (Years)</label>
                <input type="number" id="experience" name="experience" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="license_number">License Number</label>
                <input type="text" id="license_number" name="license_number" required>
            </div>
            <div class="form-group">
                <label for="availability">Availability</label>
                <textarea id="availability" name="availability" rows="4" required></textarea>
            </div>
            <button type="submit" class="save-btn">Save Profile</button>
        </form>
    </div>
</body>
</html>
