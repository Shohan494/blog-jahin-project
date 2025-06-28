<?php
session_start();
include "../../model/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['post_id'])) {
    header("Location: see_post.php?message=Invalid+post+ID");
    exit();
}

$post_id = (int)$_GET['post_id'];

// Fetch post details
$sql = "SELECT * FROM posts WHERE post_id = $post_id";
$post_result = mysqli_query($conn, $sql);
if (!$post_result || mysqli_num_rows($post_result) == 0) {
    header("Location: see_post.php?message=Post+not+found");
    exit();
}
$post = mysqli_fetch_assoc($post_result);

// Fetch categories for the post
$sql_categories = "SELECT c.name FROM categories c 
                  JOIN post_categories pc ON c.category_id = pc.category_id 
                  WHERE pc.post_id = $post_id";
$categories_result = mysqli_query($conn, $sql_categories);
$categories = [];
if ($categories_result && mysqli_num_rows($categories_result) > 0) {
    while ($row = mysqli_fetch_assoc($categories_result)) {
        $categories[] = $row['name'];
    }
}
mysqli_free_result($categories_result);

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : NULL;
    $author_id = isset($_SESSION['author_id']) ? (int)$_SESSION['author_id'] : NULL;

    $sql = "INSERT INTO comments (post_id, user_id, author_id, content) VALUES ($post_id, " . 
           ($user_id ? $user_id : 'NULL') . ", " . 
           ($author_id ? $author_id : 'NULL') . ", '$content')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: view_post.php?post_id=$post_id&message=Comment+added+successfully");
        exit();
    } else {
        $error = "Error adding comment: " . mysqli_error($conn);
    }
}

// Fetch comments
$sql = "SELECT * FROM comments WHERE post_id = $post_id ORDER BY created_at DESC";
$comments_result = mysqli_query($conn, $sql);

mysqli_close($conn);
?>
