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