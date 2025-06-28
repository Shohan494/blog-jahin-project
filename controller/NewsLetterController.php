<?php
session_start();
include "../../model/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
$username = htmlspecialchars($_SESSION['username'] ?? 'Unknown');
$message = '';

$sql_users = "SELECT user_id, username, email FROM users ORDER BY username";
$result_users = mysqli_query($conn, $sql_users);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_subscriber'])) {
    $email = trim(mysqli_real_escape_string($conn, $_POST['email'] ?? ''));
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        $sql = "SELECT id, status FROM subscribers WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row['status'] == 'confirmed') {
                $message = "This email is already subscribed.";
            } elseif ($row['status'] == 'pending') {
                $message = "A confirmation email has already been sent to this address.";
            } elseif ($row['status'] == 'unsubscribed') {
                $sql = "UPDATE subscribers SET status = 'pending' WHERE id = {$row['id']}";
                mysqli_query($conn, $sql);
                if (mysqli_query($conn, $sql)) {
                    $message = "Subscriber Again Subscribed successfully.";
                } else {
                    $message = "Error unsubscribing: " . mysqli_error($conn);
                }
            }
        } else {
            $sql = "INSERT INTO subscribers (email, status) VALUES ('$email', 'pending')";
            if (mysqli_query($conn, $sql)) {
                $sendEmail = true;
                if ($sendEmail) {
                    $message = "Subscriber added. A confirmation email has been sent to $email.";
                } else {
                    $message = "Failed to send confirmation email.";
                }
            } else {
                $message = "Error adding subscriber: " . mysqli_error($conn);
            }
        }
        mysqli_free_result($result);
    }
}

// Handle unsubscribe action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['unsubscribe_id'])) {
    $unsubscribe_id = (int)$_POST['unsubscribe_id'];
    $sql = "UPDATE subscribers SET status = 'unsubscribed' WHERE id = $unsubscribe_id";
    if (mysqli_query($conn, $sql)) {
        $message = "Subscriber unsubscribed successfully.";
    } else {
        $message = "Error unsubscribing: " . mysqli_error($conn);
    }
}

// Fetch subscribers
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$sql = "SELECT id, email, status, created_at FROM subscribers";
if ($status_filter) {
    $sql .= " WHERE status = '$status_filter'";
}
$sql .= " ORDER BY created_at DESC";
$result_subscribers = mysqli_query($conn, $sql);

mysqli_close($conn);
?>