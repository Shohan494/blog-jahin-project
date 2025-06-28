<?php
session_start();
include "../../model/database.php";

$username = $firstname = $lastname = $email = $password = $role = $done = $err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $username = !empty($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : "";
    $firstname = !empty($_POST['firstname']) ? mysqli_real_escape_string($conn, $_POST['firstname']) : "";
    $lastname = !empty($_POST['lastname']) ? mysqli_real_escape_string($conn, $_POST['lastname']) : "";
    $email = !empty($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : "";
    $password = !empty($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : "";

    $role = "user"; // default

    // Check all required fields
    if ($username && $firstname && $lastname && $email && $password) {
        // Check if username exists
        $sqlUserCheck = "SELECT username FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sqlUserCheck);

        if (!$result) {
            $err = "Error checking username: " . mysqli_error($conn);
        } elseif (mysqli_num_rows($result) > 0) {
            $err = "Username already exists!";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user with role
            $sql = "INSERT INTO users (username, firstname, lastname, email, password, role)
                    VALUES ('$username', '$firstname', '$lastname', '$email', '$hashedPassword', '$role')";

            if (mysqli_query($conn, $sql)) {
                $done = "Sign Up Success";
                // Optionally redirect after success
                 header("Location: login.php");
            } else {
                $err = "Database error during insert: " . mysqli_error($conn);
            }
        }
    } else {
        $err = "All fields are required";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <style>
        /* your existing styles here */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .form-panel {
            flex-basis: 50%;
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #f2f2f2;
        }

        .form-panel h2 {
            margin-bottom: 30px;
        }

        form {
            width: 100%;
            max-width: 300px;
        }

        .form-panel h4 {
            margin: 15px 0 5px;
        }

        .form-panel input[type="text"],
        .form-panel input[type="email"],
        .form-panel input[type="password"],
        .form-panel select {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-panel input[type="submit"] {
            padding: 12px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            margin-top: 20px;
            cursor: pointer;
            width: 40%;
        }

        .form-panel input[type="submit"]:hover {
            background-color: #45a049;
        }

        .image-panel {
            flex-basis: 50%;
            overflow: hidden;
        }

        .image-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .message {
            margin-top: 20px;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Left panel: Form -->
        <div class="form-panel">
            <h2>Sign Up</h2>

            <form action="" method="post">
                <h4>Username:</h4>
                <input type="text" name="username" required>

                <h4>First Name:</h4>
                <input type="text" name="firstname" required>

                <h4>Last Name:</h4>
                <input type="text" name="lastname" required>

                <h4>Email:</h4>
                <input type="email" name="email" required>

                <h4>Password:</h4>
                <input type="password" name="password" required>

                <input type="submit" value="Sign Up">

                <?php if (!empty($done)): ?>
                    <p class="message success"><?php echo $done; ?></p>
                <?php endif; ?>

                <?php if (!empty($err)): ?>
                    <p class="message error"><?php echo $err; ?></p>
                <?php endif; ?>
            </form>
        </div>

        <!-- Right panel: Image -->
        <div class="image-panel">
            <img src="../image/signup.jpg" alt="Signup Image">
        </div>
    </div>

</body>

</html>