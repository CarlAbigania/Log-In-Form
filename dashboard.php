<?php
// Start session to track the user's login state
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit(); // Stop further script execution
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Page</title>

    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* General body styles */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, yellow, red);    
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Styling for the dashboard container */
        .dashboardContainer {
            width: 400px;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.6);
            text-align: center;
            position: relative;
            animation: mySlide;
            animation-duration: 2s;
            border-top: dotted;
            border-bottom: dotted;
            border-left: groove;
            border-right: groove;
        }

        /* Logout button styles */
        .btn-logout {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }

        /* Delete account button styles */
        .delete-btn {
            padding: 10px 15px;
            font-size: 14px;
            background-color: #ff4444;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }

        .delete-btn:hover {
            background-color: #d63031;
        }

        /* Headings and text styling */
        h2 {
            margin-top: 0;
            color: #343a40;
            text-shadow: 0px 0px 5px yellow, 0px -5px 5px red;  
        }

        p {
            color: #6c757d;
            margin-bottom: 20px;
        }

        /* Button container styling */
        .btnContainer {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }

        /* General button styles */
        .btn {
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 48%;
        }

        /* Profile buttons */
        .btn-profile {
            background-color: #007bff;
            color: white;
        }

        .btn-profile:hover {
            background-color: #0056b3;
        }

        /* Styling for the user icon */
        .fa-solid {
            font-size: 122px;
            color: #007bff;
            margin-bottom: 20px;
        }

        @keyframes mySlide {
            from{margin-left: 30%;}
            to{margin-left: -0%;}
        }
    </style>
</head>

<body>
    <div class="dashboardContainer">

        <!-- Logout form -->
        <form action="logout.php" method="POST">
            <input type="submit" class="btn-logout" value="Logout" name="logout">
        </form>

        <!-- Dashboard welcome message -->
        <h2>Welcome to the Dashboard</h2>
        <p>You are logged in successfully.</p>

        <!-- User icon -->
        <i class="fa-solid fa-user"></i>

        <!-- Buttons to update or view profile -->
        <div class="btnContainer">
            <button class="btn btn-profile" onclick="window.location.href='update.php'">Update Profile</button>
            <button class="btn btn-profile" onclick="window.location.href='read.php'">View Profile</button>
        </div>

        <!-- Delete account form with confirmation prompt -->
        <form action="delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action is irreversible.');">
            <button type="submit" class="delete-btn">Delete Account</button>
        </form>

    </div>
</body>

</html>