<?php
session_start();
include "../../model/database.php";

// Fetch all categories
$sql_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($conn, $sql_categories);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author_id = (int)$_POST['author_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $categories = isset($_POST['categories']) ? $_POST['categories'] : [];

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Create New Post</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title " required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="6" required></textarea>
        </div>
        <div class="form-group">
            <label for="author_id">Author ID:</label>
            <input type="number" id="author_id" name="author_id" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </div>
        <div class="form-group">
            <label for="categories">Categories:</label>
            <select id="categories" name="categories[]" multiple>
                <?php while ($category = mysqli_fetch_assoc($result_categories)): ?>
                    <option value="<?php echo $category['category_id']; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit">Create Post</button>
    </form>
</body>
</html>