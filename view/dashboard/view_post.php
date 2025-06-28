<?php
session_start();
include "../../model/database.php";

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        h1, h2 {
            color: #333;
        }
        .top-bar {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }
        .home-btn {
            background-color: #007BFF;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .home-btn:hover {
            background-color: #0056b3;
        }
        .post-container {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }
        .post-meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .comment-form {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }
        .comment-form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        .comment-form textarea {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            resize: vertical;
            min-height: 100px;
        }
        .comment-form input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            margin-top: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .comment-form input[type="submit"]:hover {
            background-color: #218838;
        }
        .comments-container {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }
        .comment {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .comment:last-child {
            border-bottom: none;
        }
        .comment-meta {
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .message {
            margin: 20px auto;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            width: 80%;
            border-radius: 5px;
            text-align: center;
        }
        .error {
            margin: 20px auto;
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            width: 80%;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>View Post</h1>

    <div class="top-bar">
        <a href="see_post.php" class="home-btn">Back to All Posts</a>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="message"><?= htmlspecialchars($_GET['message']) ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="post-container">
        <h2><?= htmlspecialchars($post['title']) ?></h2>
        <div class="post-meta">
            Author ID: <?= htmlspecialchars($post['author_id']) ?> | 
            Status: <?= htmlspecialchars($post['status']) ?> | 
            Created: <?= htmlspecialchars($post['created_at']) ?> |
            Categories: <?= !empty($categories) ? htmlspecialchars(implode(', ', $categories)) : 'No categories' ?>
        </div>
        <div class="post-content">
            <?= $post['content'] ?>
        </div>
    </div>

    <div class="comment-form">
        <h2>Add Comment</h2>
        <form method="POST" action="">
            <label for="content">Comment</label>
            <textarea id="content" name="content" required></textarea>
            <input type="submit" value="Add Comment">
        </form>
    </div>

    <div class="comments-container">
        <h2>Comments</h2>
        <?php if (mysqli_num_rows($comments_result) > 0): ?>
            <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                <div class="comment">
                    <div class="comment-meta">
                        By: <?= $comment['user_id'] ? 'User #' . htmlspecialchars($comment['user_id']) : 'Author #' . htmlspecialchars($comment['author_id']) ?> | 
                        Posted: <?= htmlspecialchars($comment['created_at']) ?>
                    </div>
                    <div class="comment-content">
                        <?= htmlspecialchars($comment['content']) ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No comments yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>