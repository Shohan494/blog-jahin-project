<?php
require_once '../../controller/LoginController.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In</title>
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
            padding: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #f2f2f2;
        }
        .form-panel h2 {
            margin-bottom: 20px;
        }
        form {
            width: 100%;
            max-width: 300px;
        }
        .form-panel h4 {
            margin: 15px 0 5px;
        }
        .form-panel input[type="text"],
        .form-panel input[type="password"] {
            width: 100%;
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
            width: 100%;
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
        }
        .password-wrapper {
            display: flex;
            flex-direction: column;
        }
        .password-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .password-label h4 {
            margin: 0;
        }
        .password-label a {
            font-size: 0.9em;
            text-decoration: none;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
        .success {
            color: green;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left panel: Form -->
        <div class="form-panel">
            <h2>Log In</h2>
            <h5>Don't have an account yet?
                <a href="../dashboard/signup.php">Sign up</a>
            </h5>

            <!-- Display error message -->
            <?php if (!empty($err)): ?>
                <p class="error"><?php echo htmlspecialchars($err); ?></p>
            <?php endif; ?>


            <!-- Display error message -->
            <?php if (!empty($_SESSION['message'])): ?>
                <p class="error"><?php echo htmlspecialchars($_SESSION['message']); ?></p>
            <?php endif; ?>

            <form action="" method="post">
                <h4>Username:</h4>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

                <div class="password-wrapper">
                    <div class="password-label">
                        <h4>Password:</h4>
                        <a href="forgetpass.php">Forget Password?</a>
                    </div>
                    <input type="password" name="password" required>
                </div>

                <div style="margin-top: 10px;">
                    <label>
                        <input type="checkbox" name="remember_me"> Remember me
                    </label>
                </div>

                <input type="submit" value="Log In">
            </form>
        </div>

        <!-- Right panel: Image -->
        <div class="image-panel">
            <img src="../image/login.jpg" alt="Log In Image">
        </div>
    </div>
</body>
</html>