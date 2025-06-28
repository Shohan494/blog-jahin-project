<?php
session_start();
include "../../model/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle delete request
if (isset($_GET['post_id'], $_GET['author_id'])) {
    $post_id = (int) $_GET['post_id'];
    $author_id = (int) $_GET['author_id'];

    $author_check = $conn->prepare("SELECT author_id FROM authors WHERE author_id = ?");
    $author_check->bind_param("i", $author_id);
    $author_check->execute();
    $author_check->store_result();

    if ($author_check->num_rows === 1) {
        $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ? AND author_id = ?");
        $stmt->bind_param("ii", $post_id, $author_id);
        if ($stmt->execute()) {
            header("Location: see_post.php?message=Post+deleted+successfully");
            exit();
        } else {
            $error = "Error deleting post: " . $conn->error;
        }
    } else {
        $error = "Author not found. Cannot delete post.";
    }
}

// Handle update post form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = (int) $_POST['post_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, status = ? WHERE post_id = ?");
    $stmt->bind_param("sssi", $title, $content, $status, $post_id);

    if ($stmt->execute()) {
        header("Location: see_post.php?message=Post+updated+successfully");
        exit();
    } else {
        $error = "Error updating post: " . $conn->error;
    }
}

// Fetch posts
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

// If editing, fetch post data
$editing = false;
$edit_post = null;
if (isset($_GET['edit_post'])) {
    $editing = true;
    $edit_post_id = (int) $_GET['edit_post'];
    $stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $edit_post_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $edit_post = $res->fetch_assoc();
}
?>