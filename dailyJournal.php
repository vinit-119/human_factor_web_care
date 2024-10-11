<?php
include("navigation.php");
// dailyJournal.php
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

// Handle form submission to add a new journal entry
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mood = $_POST['mood'];
    $entry = $_POST['entry'];
    $date = date('Y-m-d');

    $query = "INSERT INTO daily_journals (patient_id, date, mood, entry) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isss', $user_id, $date, $mood, $entry);

    if ($stmt->execute()) {
        echo '<script>alert("Journal entry added successfully!");</script>';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
}

// Fetch previous journal entries for the patient
$journalQuery = "SELECT date, mood, entry FROM daily_journals WHERE patient_id = ? ORDER BY date DESC";
$journalStmt = $conn->prepare($journalQuery);
$journalStmt->bind_param('i', $user_id);
$journalStmt->execute();
$journalResult = $journalStmt->get_result();
$journalEntries = $journalResult->fetch_all(MYSQLI_ASSOC);

$journalStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Journal</title>
    <style>
        /* Daily Journal Page CSS */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        .journal-page {
            max-width: 900px;
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

        .journal-form, .journal-entries {
            margin-top: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        .save-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .save-btn:hover {
            background-color: #45a049;
        }

        .journal-entry {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .journal-entry h3 {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="journal-page">
        <h1>Daily Journal</h1>
        <h2>Record Your Thoughts and Feelings</h2>

        <!-- Form to add new journal entry -->
        <div class="journal-form">
            <form action="dailyJournal.php" method="POST">
                <div class="form-group">
                    <label for="mood">Mood</label>
                    <select id="mood" name="mood" required>
                        <option value="Happy">Happy</option>
                        <option value="Neutral">Neutral</option>
                        <option value="Sad">Sad</option>
                        <option value="Anxious">Anxious</option>
                        <option value="Excited">Excited</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="entry">Journal Entry</label>
                    <textarea id="entry" name="entry" rows="4" placeholder="Write about your day..." required></textarea>
                </div>
                <button type="submit" class="save-btn">Save Entry</button>
            </form>
        </div>

        <!-- Display previous journal entries -->
        <div class="journal-entries">
            <h2>Your Previous Entries</h2>
            <?php if (!empty($journalEntries)): ?>
                <?php foreach ($journalEntries as $entry): ?>
                    <div class="journal-entry">
                        <h3><?php echo htmlspecialchars($entry['date']); ?> - Mood: <?php echo htmlspecialchars($entry['mood']); ?></h3>
                        <p><?php echo htmlspecialchars($entry['entry']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No journal entries found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
