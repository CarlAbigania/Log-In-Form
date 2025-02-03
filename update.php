<?php
// Start session to maintain user login state
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

try {
    // Establish a connection to the database using PDO
    $conn = new PDO("mysql:host=localhost;dbname=crud_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
} catch (PDOException $e) {
    // If the connection fails, display an error message
    echo "Connection failed: " . $e->getMessage();
}

$message = "";
$user_id = $_SESSION['user_id']; // Get user ID from session

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Gather the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $fields = []; // Array to hold fields that need updating
    $params = [':user_id' => $user_id]; // Array to hold parameters for query

    // Check if each field is not empty and add it to the query
    if (!empty($name)) {
        $fields[] = "name = :name";
        $params[':name'] = $name;
    }
    if (!empty($email)) {
        $fields[] = "email = :email";
        $params[':email'] = $email;
    }
    if (!empty($username)) {
        $fields[] = "username = :username";
        $params[':username'] = $username;
    }
    if (!empty($password)) {
        $fields[] = "password = :password";
        $params[':password'] = $password;
    }

    // If there are fields to update, construct and execute the update query
    if (!empty($fields)) {
        $updateSql = "UPDATE users SET " . implode(', ', $fields) . " WHERE user_id = :user_id";
        try {
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->execute($params);
            $message = "Profile updated successfully!";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "No fields to update.";
    }

    // Fetch updated user data after submission
    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Fetch user data when the page is first loaded
    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// If the user data is not found, display an error message
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
    <title>Update Profile</title>
    <style>
        /* Styles for form and layout */
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

        main {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 400px;
            border-style: groove;
            border-width: 18px;
            box-shadow: 0px 0px 10px yellow, 0px -5px 10px red;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            color: #2d3436;
            text-shadow: 0px 0px 5px yellow, 0px -5px 5px red;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: 500;
            color: #636e72;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #dfe6e9;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f0f4f8;
        }

        input:focus {
            outline: none;
            border-color: #74b9ff;
            box-shadow: 0 0 5px rgba(116, 185, 255, 0.5);
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 14px;
            color: #007bff;
        }

        input[type="submit"] {
            background: #0984e3;
            color: white;
            border: none;
            padding: 12px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: #74b9ff;
        }

        .message {
            text-align: center;
            color: red;
            margin-bottom: 15px;
            font-weight: 600;
        }

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

    <script>
        // Toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleBtn = document.getElementById("togglePassword");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleBtn.innerHTML = "Hide";
            } else {
                passwordField.type = "password";
                toggleBtn.innerHTML = "Show";
            }
        }
    </script>
</head>

<body>
    <main>
        <h1>Update Profile</h1>
        <?php if (isset($message) && !empty($message)) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>
        <form action="update.php" method="POST">
            <label for="name">Name: </label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($userData['name']); ?>" placeholder="Enter new name">

            <label for="email">Email: </label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($userData['email']); ?>" placeholder="Enter new email">

            <label for="username">Username: </label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($userData['username']); ?>" placeholder="Enter new username">

            <label for="password">Password: </label>
            <div class="password-container">
                <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($userData['password']); ?>" placeholder="Enter new password">
                <span id="togglePassword" class="toggle-password" onclick="togglePassword()">Show</span>
            </div>

            <input type="submit" value="Submit">
        </form>

        <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
    </main>
</body>

</html>
