<?php
include("navigation.php");
// patientProfile.php
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

// Handle the form submission to update patient profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $medical_history = $_POST['medical_history'];

    // Handle file upload for profile picture
    $target_dir = "uploads/";
    $profile_picture = $target_dir . basename($_FILES["profile_picture"]["name"]);
    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture);

    // Insert or update patient details in the database
    $query = "INSERT INTO patients (user_id, age, phone, address, gender, medical_history, profile_picture) 
              VALUES (?, ?, ?, ?, ?, ?, ?) 
              ON DUPLICATE KEY UPDATE age = VALUES(age), phone = VALUES(phone), address = VALUES(address), 
              gender = VALUES(gender), medical_history = VALUES(medical_history), profile_picture = VALUES(profile_picture)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('iisssss', $user_id, $age, $phone, $address, $gender, $medical_history, $profile_picture);

    if ($stmt->execute()) {
        // Redirect to the patient dashboard after successful profile update
        header('Location: patientDashboard.php');
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
    <title>Patient Profile</title>
    <style>
        /* Patient Profile CSS Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        .patient-profile-page {
            max-width: 1200px;
            margin: 0 auto;
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
            max-width: 600px;
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
    <div class="patient-profile-page">
        <h1>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>
        <h2>Complete Your Profile</h2>

        <form action="patientProfile.php" method="POST" enctype="multipart/form-data" class="profile-form">
            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="medical_history">Medical History</label>
                <textarea id="medical_history" name="medical_history" rows="4"></textarea>
            </div>
            <button type="submit" class="save-btn">Save Profile</button>
        </form>
    </div>
</body>
</html>

