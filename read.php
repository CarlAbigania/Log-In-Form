<?php
// Start the session to check if the user is logged in
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// Establish connection to the database
try {
    $conn = new PDO("mysql:host=localhost;dbname=crud_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, display error
    echo "Connection failed: " . $e->getMessage();
}

// Fetch user ID from the session
$user_id = $_SESSION['user_id'];

// Prepare and execute SQL to retrieve user information from the database
$fetchSql = "SELECT name, email, username, password, created_at FROM users WHERE user_id = :user_id";
$fetchStmt = $conn->prepare($fetchSql);
$fetchStmt->execute([':user_id' => $user_id]);
$userData = $fetchStmt->fetch(PDO::FETCH_ASSOC);

// If user data is not found, show an error
if (!$userData) {
    echo "User not found.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <style>
        /* Page and body styling */
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, yellow, red);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Main content box */
        main {
            max-width: 600px;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 10px;
            border-style: groove;
            border-width: 18px;
            box-shadow: 0px 0px 10px yellow, 0px -5px 10px red;
        }

        h2 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
            font-size: 28px;
            text-shadow: 0px 0px 5px yellow, 0px -5px 5px red;
        }

        /* User information card styling */
        .user-card {
            background-color: #f9f9fc;
            border: 1px solid #e1e1e8;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease-in-out;
        }

        .user-card:hover {
            transform: scale(1.02);
        }

        .user-card h3 {
            margin: 0 0 10px;
            font-size: 22px;
            color: #333;
        }

        .user-card p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }

        .label {
            font-weight: bold;
            color: #777;
        }

        .created-at {
            font-size: 14px;
            color: #999;
        }

        /* Back to dashboard button styling */
        .btn-back {
            position: absolute;
            top: 2px;
            right: 3px;
            display: inline-block;
            padding: 12px 20px;
            font-size: 16px;
            color: white;
            background: #636e72;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-back:hover {
            background: #b2bec3;
        }
    </style>
</head>

<body>

    <main>
        <h2>User Information</h2>

        <div class="user-card">
            <!-- Display user's name -->
            <h3><?php echo htmlspecialchars($userData["name"]); ?></h3>
            <!-- Display user's email -->
            <p><span class="label">Email:</span> <?php echo htmlspecialchars($userData["email"]); ?></p>
            <!-- Display user's username -->
            <p><span class="label">Username:</span> <?php echo htmlspecialchars($userData["username"]); ?></p>
            <!-- Display user's password (hashed passwords should be stored, not displayed) -->
            <p><span class="label">Password:</span> <?php echo htmlspecialchars($userData["password"]); ?></p>
            <!-- Display when the account was created -->
            <p class="created-at"><span class="label">Account Created At:</span> <?php echo htmlspecialchars($userData["created_at"]); ?></p>
        </div>

        <!-- Back to dashboard button -->
        <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
    </main>

</body>

</html>
