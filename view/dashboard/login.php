<?php
session_start();
include "../../model/database.php"; // Assumes blog/view/ directory

// Initialize variables
$username = $password = $err = "";

// Check for remember_me cookie
if (isset($_SESSION['user_id']) && isset($_COOKIE['username'])) {
    $username = mysqli_real_escape_string($conn, $_COOKIE['username']);
    $sql = "SELECT user_id, role FROM users WHERE username = '$username' AND role = 'admin'";
    $result = mysqli_query($conn, $sql);

    print_r($result);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['role'] = $row['role'];
        header("Location: ../dashboard/admindashboard.php");
        exit();
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
        $password = trim($_POST['password']);

        // Fetch user by username
        $sql = "SELECT user_id, username, password, role FROM users WHERE username = '$username' AND role = 'admin'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $dbPasswordHash = $row['password'];
            $dbUserId = $row['user_id'];
            $dbRole = $row['role'];

            // Verify password
            if (password_verify($password, $dbPasswordHash)) {
                if (!empty($dbRole) && !empty($dbUserId)) {
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = $dbUserId;
                    $_SESSION['role'] = $dbRole;

                    if (!empty($_POST['remember_me'])) {
                        setcookie("username", $username, time() + (86400 * 30), "/");
                    } else {
                        // Clear cookie if remember_me is not checked
                        setcookie("username", "", time() - 3600, "/");
                    }

                    header("Location: ../dashboard/admindashboard.php");
                    exit();
                } else {
                    $err = "User role or ID invalid!";
                }
            } else {
                $err = "Incorrect password!";
            }
        } else {
            $err = "Admin not found!";
        }
        mysqli_free_result($result);
    } else {
        $err = "Please fill in both username and password.";
    }
}

mysqli_close($conn);
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