


<?php
require_once '../../controller/SignUpController.php';
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