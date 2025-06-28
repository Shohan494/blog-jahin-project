<?php

session_start();
include "../../model/database.php";

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