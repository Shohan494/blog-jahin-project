<?php
session_start();
include "../../model/database.php"; // Adjust path if needed

// Check admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
header("Location: login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete_author'])) {
    $author_id = (int) $_GET['delete_author'];
    $sql = "DELETE FROM authors WHERE author_id = $author_id";
    mysqli_query($conn, $sql);
    header("Location: authors_info.php?message=Author+deleted+successfully");
    exit;
}

// Handle add/update author
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $author_id = isset($_POST['author_id']) ? (int) $_POST['author_id'] : null;

    if ($author_id) {
        $sql = "UPDATE authors SET name='$name', email='$email', bio='$bio' WHERE author_id=$author_id";
    } else {
        $sql = "INSERT INTO authors (name, email, bio, created_at) VALUES ('$name', '$email', '$bio', NOW())";
    }
    mysqli_query($conn, $sql);
    header("Location: authors_info.php?message=Author+saved+successfully");
    exit;
}

// Fetch authors
$sql = "SELECT * FROM authors ORDER BY created_at DESC";
$authors = mysqli_query($conn, $sql);

// Edit mode
$editing = false;
$edit_data = ['author_id' => '', 'name' => '', 'email' => '', 'bio' => ''];
if (isset($_GET['edit'])) {
    $editing = true;
    $edit_id = (int) $_GET['edit'];
    $sql = "SELECT * FROM authors WHERE author_id = $edit_id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_data = mysqli_fetch_assoc($result);
    }
}

mysqli_close($conn);
?>