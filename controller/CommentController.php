<?php
session_start();
include "../../model/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Delete comment by comment_id
if (isset($_GET['delete_comment'])) {
    $comment_id = (int) $_GET['delete_comment'];

    $stmt = $conn->prepare("DELETE FROM comments WHERE comment_id = ?");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    header("Location: manage_comments.php?message=Comment+deleted");
    exit();
}

// Update comment content (optional, you can add a form and handler if you want)

// Fetch all comments ordered by created_at DESC
$sql = "SELECT * FROM comments ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
