<?php include 'navigation.php'; ?>

<?php
// login.php
// Database connection settings
$servername = 'localhost:3308';
$username = 'root'; // Use your database username
$password = ''; // Use your database password
$dbname = 'web_care';

// Start session
session_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $query = "SELECT user_id, first_name, role, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $first_name, $role, $hashed_password);
    $stmt->fetch();

    // Validate password and start the session
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        // Set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['role'] = $role;

        if ($role == 'patient') {
            // Check if the patient's profile is complete
            $profileQuery = "SELECT age, phone, address, gender FROM patients WHERE user_id = ?";
            $profileStmt = $conn->prepare($profileQuery);
            $profileStmt->bind_param('i', $user_id);
            $profileStmt->execute();
            $profileStmt->store_result();
            if ($profileStmt->num_rows > 0) {
                // If profile exists, redirect to dashboard
                header('Location: patientDashboard.php');
            } else {
                // Otherwise, redirect to the profile page
                header('Location: patientProfile.php');
            }
            $profileStmt->close();
        } elseif ($role == 'therapist') {
            header('Location: therapistDashboard.php');
        } elseif ($role == 'admin') {
            header('Location: adminDashboard.php');
        } elseif ($role == 'professional_staff') {
            header('Location: staffDashboard.php');
        } elseif ($role == 'auditor') {
            header('Location: auditorDashboard.php');
        }
        exit();
    } else {
        echo 'Invalid email or password. Please try again.';
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
    <title>Login</title>
    <style>
        /* Login Page CSS */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        .login-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #4CAF50;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form action="login.php" method="POST" class="login-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="submit-btn">Login</button>
        </form>
    </div>
</body>
</html>
