<?php
// logout.php
// Start session
session_start();

// Destroy the session to log out the user
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session itself

// Redirect to the home page after logging out
header('Location: home.php');
exit();
?>
