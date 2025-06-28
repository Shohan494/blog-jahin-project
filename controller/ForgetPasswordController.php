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
        $sqlUserCheck = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
        $result = mysqli_query($conn, $sqlUserCheck);

        if (mysqli_num_rows($result) > 0) {
            // Update password
            $sqlUpdate = "UPDATE users SET password = '$hashedPassword' WHERE username = '$username' AND email = '$email'";
            if (mysqli_query($conn, $sqlUpdate)) {
                $_SESSION['message'] = "Password updated successfully!";

                header("Location: login.php");
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