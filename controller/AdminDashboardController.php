<?php
session_start();
include "../../model/database.php";

// Check admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username'] ?? 'Unknown');

// Fetch dashboard metrics
$total_posts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM posts"))['count'];
$total_categories = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM categories"))['count'];
$total_authors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM authors"))['count'];
$total_subscribers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM subscribers"))['count'];

// Fetch recent posts (limit 5)
$recent_posts = mysqli_query($conn, "SELECT post_id, title, status, created_at FROM posts ORDER BY created_at DESC LIMIT 5");

// Fetch recent subscribers (limit 5)
$recent_subscribers = mysqli_query($conn, "SELECT id, email, status, created_at FROM subscribers ORDER BY created_at DESC LIMIT 5");

mysqli_close($conn);
?>