<?php
// Start session to track the user's login state
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit(); 
}

// Check if the logout form is submitted
if (isset($_POST['logout'])) {
    // Destroy the session to log out the user
    session_destroy();
    // Redirect the user back to the login page after logout
    header("Location: login.php");
    exit(); 
}
?>
