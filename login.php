<?php
// Start session to manage user login state
session_start();

// Attempt database connection using PDO
try {
    $conn = new PDO("mysql:host=localhost; dbname=crud_db", "root", ""); // Connect to MySQL database
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
} catch (PDOException $e) {
    // Handle database connection failure
    echo "Connection failed: " . $e->getMessage();
}

$error = ""; // Initialize error message variable

// Check if form is submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input for email and password
    $email = $_POST['email'];
    $inputpassword = $_POST['password'];

    // Check if email and password are provided
    if (empty($email) || empty($inputpassword)) {
        $error = "Please provide both email and password"; // Set error message if fields are empty
    } else {
        // Prepare SQL query to check if user exists with given email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email); // Bind email parameter to the query
        $stmt->execute(); // Execute the query
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the user data as an associative array

        // Check if user exists and password matches
        if ($user && $inputpassword == $user["password"]) {
            // Set session variables for logged-in state
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            // Redirect to dashboard page upon successful login
            header("Location: dashboard.php");
            exit();
        } else {
            // Set error message if login fails (invalid email or password)
            $error = "Invalid email or password";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn Page</title>

    <style>
        /* Styles for the login page layout */
        body {
            background-image: url('https://images.unsplash.com/photo-1540270776932-e72e7c2d11cd?ixlib=rb-4.0.3');
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            font-family: 'Roboto', 'Arial', sans-serif;
            font-size: 16px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }

        .logInContainer {
            text-align: center;
            background-color: transparent;
            backdrop-filter: blur(50px);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        /* Styles for headings and form elements */
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            font-size: 14px;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"],
        input[type="email"] {
            display: block;
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        /* Error message styling */
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        #register {
            color: red;
        }

        /* Password visibility toggle styles */
        .password-container {
            position: relative;
            display: block;
            width: 100%;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 12px;
            color: #007bff;
        }
    </style>

    <script>
        // Toggle password visibility function
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleBtn = document.getElementById("togglePassword");

            // Show/hide password and update toggle button text
            if (passwordField.type === "password") {
                passwordField.type = "text"; // Show password
                toggleBtn.innerHTML = "Hide"; // Update button to "Hide"
            } else {
                passwordField.type = "password"; // Hide password
                toggleBtn.innerHTML = "Show"; // Update button to "Show"
            }
        }
    </script>
</head>

<body>
    <div class="logInContainer">
        <h2>Log In</h2>

        <!-- Display error message if present -->
        <?php if (!empty($error)) {
            echo "<p class='error'>$error</p>";
        } ?>

        <!-- Login form -->
        <form action="login.php" method="POST">
            <label for="email">Email: </label>
            <input type="email" name="email" id="email" placeholder="Enter email"> <!-- Email input -->

            <label for="password">Password: </label>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Enter password"> <!-- Password input -->
                <span id="togglePassword" class="toggle-password" onclick="togglePassword()">Show</span> <!-- Password toggle button -->
            </div>

            <input type="submit" value="Login"> <!-- Submit button -->
        </form>

        <p>or</p>

        <!-- Link to registration page -->
        <a id="register" href="registration.php">Create account?</a><br>
    </div>
</body>

</html>
