<?php
session_start();
include "../../model/database.php";

$username = $email = $newPassword = $done = $err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['new_password'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Check if username and email match
        $sqlUserCheck = "SELECT * FROM user WHERE username = '$username' AND email = '$email'";
        $result = mysqli_query($conn, $sqlUserCheck);

        if (mysqli_num_rows($result) > 0) {
            // Update password
            $sqlUpdate = "UPDATE user SET password = '$hashedPassword' WHERE username = '$username' AND email = '$email'";
            if (mysqli_query($conn, $sqlUpdate)) {
                $done = "Password updated successfully!";
            } else {
                $err = "Database error: " . mysqli_error($conn);
            }
        } else {
            $err = "No account found with that username and email!";
        }
    } else {
        $err = "Username, email, and new password are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Forget Password</title>
    <style>
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
        .form-panel input[type="password"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-panel input[type="submit"] {
            padding: 12px;
            font-size: 16px;
            background-color: #4caf50;
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
            margin-top: 10px;
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
            <h2>Forget Password?</h2>
            <form method="post" action="">
                <h4>Username:</h4>
                <input type="text" name="username" required />

                <h4>Email:</h4>
                <input type="email" name="email" required />

                <h4>New Password:</h4>
                <input type="password" name="new_password" required />

                <input type="submit" value="Save" />
            </form>

            <?php if (!empty($done)): ?>
                <p class="message success"><?php echo $done; ?></p>
            <?php endif; ?>

            <?php if (!empty($err)): ?>
                <p class="message error"><?php echo $err; ?></p>
            <?php endif; ?>
        </div>

        <!-- Right panel: Image -->
        <div class="image-panel">
            <img src="../image/forgetpass.jpg" alt="Forget Password Image" />
        </div>
    </div>

</body>

</html>