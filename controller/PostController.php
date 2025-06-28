<?php
session_start();
include "../../model/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all categories
$sql_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($conn, $sql_categories);

// Fetch all users for author dropdown
$sql_users = "SELECT author_id, name FROM authors";
$result_users = mysqli_query($conn, $sql_users);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author_id = (int)$_POST['author_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $categories = isset($_POST['categories']) ? $_POST['categories'] : [];

    // Validate author_id exists in users table
    $sql_check_author = "SELECT COUNT(*) AS count FROM authors WHERE author_id = $author_id";
    $result_check_author = mysqli_query($conn, $sql_check_author);
    $author_exists = $result_check_author && mysqli_fetch_assoc($result_check_author)['count'] > 0;
    mysqli_free_result($result_check_author);

    if (!$author_exists) {
        echo "<p style='color: red;'>Error: Selected author does not exist.</p>";
    } else {
        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Insert post
            $sql = "INSERT INTO posts (title, content, author_id, status) VALUES ('$title', '$content', $author_id, '$status')";
            if (!mysqli_query($conn, $sql)) {
                throw new Exception("Error creating post: " . mysqli_error($conn));
            }

            $post_id = mysqli_insert_id($conn);

            // Insert post-category relationships
            foreach ($categories as $category_id) {
                $category_id = (int)$category_id;
                $sql = "INSERT INTO post_categories (post_id, category_id) VALUES ($post_id, $category_id)";
                if (!mysqli_query($conn, $sql)) {
                    throw new Exception("Error linking category: " . mysqli_error($conn));
                }
            }

            mysqli_commit($conn);
            header("Location: see_post.php");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    }
}

mysqli_close($conn);
?>