<?php
// Start session to track user login state
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    // If the user is not logged in, redirect them to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}

try {
    // Establish a PDO connection to the database
    $conn = new PDO("mysql:host=localhost;dbname=crud_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception

    // Retrieve the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Prepare the SQL query to delete the user based on their user_id
    $deleteSql = "DELETE FROM users WHERE user_id = :user_id";
    $stmt = $conn->prepare($deleteSql);

    // Execute the delete statement with the user ID
    $stmt->execute([':user_id' => $user_id]);

    // Destroy the session to log out the user after deletion
    session_destroy();

    // Redirect the user to the login page
    header("Location: login.php");
    exit();
} catch (PDOException $e) {
    // If an error occurs, output the error message
    echo "Error: " . $e->getMessage();
}
?>
