<?php
// Try connecting to the database using PDO
try {
    $conn = new PDO("mysql:host=localhost;dbname=crud_db", "root", ""); // Connect to MySQL database
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
} catch (PDOException $e) {
    // Handle connection failure and display error message
    echo "Connection failed: " . $e->getMessage();
}

// Check if the request method is POST (form submitted)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if all fields are filled
    if (!empty($name) && !empty($email) && !empty($username) && !empty($password)) {
        
        // Check if the email is already registered
        $checkEmailQuery = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $checkEmailQuery->execute(['email' => $email]);

        // If the email doesn't exist, insert the new user into the database
        if ($checkEmailQuery->rowCount() == 0) {
            $sql = "INSERT INTO users(name, email, username, password) VALUES (:name, :email, :username, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'username' => $username,
                'password' => $password
            ]);
            // Redirect to login page after successful registration
            header("refresh:1;url=login.php");
            exit();
        } else {
            // Error if email is already registered
            $error = "Email is already registered.";
        }
    } else {
        // Error if form fields are incomplete
        $error = "Fill up the form";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        /* Styles for body, form, and elements */
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

        .registrationContainer {
            text-align: center;
            background-color: transparent;
            backdrop-filter: blur(50px);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        /* Style for headings, inputs, and buttons */
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
            color: blue;
        }

        /* Style for password visibility toggle */
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
        // Toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleBtn = document.getElementById("togglePassword");

            if (passwordField.type === "password") {
                passwordField.type = "text"; // Show password
                toggleBtn.innerHTML = "Hide"; // Update button text
            } else {
                passwordField.type = "password"; // Hide password
                toggleBtn.innerHTML = "Show"; // Update button text
            }
        }
    </script>

</head>

<body>
    <div class="registrationContainer">
        <h2>Register</h2>

        <!-- Display error message if any -->
        <?php if (isset($error)) {
            echo "<p class='error'>$error</p>";
        } ?>

        <!-- Registration form -->
        <form action="registration.php" method="POST">
            <label for="name">Name: </label>
            <input type="text" name="name" id="name" placeholder="Enter name">

            <label for="email">Email: </label>
            <input type="email" name="email" id="email" placeholder="Enter email">

            <label for="username">Username: </label>
            <input type="text" name="username" id="username" placeholder="Enter username">

            <label for="password">Password: </label>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Enter password">
                <span id="togglePassword" class="toggle-password" onclick="togglePassword()">Show</span> <!-- Toggle button -->
            </div>

            <input type="submit" value="Register"> <!-- Submit button -->
        </form>

        <p>or</p>
        <a id="register" href="login.php">Have an account?</a><br> <!-- Link to login page -->
    </div>
</body>

</html>
