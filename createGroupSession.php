<?php
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

session_start();

$therapist_id = $_SESSION['user_id'];
$session_name = $_POST['session_name'];

if ($therapist_id && $session_name) {
    $query = "INSERT INTO group_sessions (therapist_id, session_name) VALUES (?, ?)";
    $stmt = $database->prepare($query);
    $stmt->bind_param("is", $therapist_id, $session_name);
    $stmt->execute();

    header('Location: therapistGroupSession.php');
} else {
    echo "Error creating group session.";
}
?>
