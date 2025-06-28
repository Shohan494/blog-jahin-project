

<?php
require_once '../../controller/PostController.php';
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
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        select[multiple] {
            height: 100px;
        }
    </style>
</head>
<body>
    <h2>Create New Post</h2>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="6" required></textarea>
        </div>
        <div class="form-group">
            <label for="author_id">Author:</label>
            <select id="author_id" name="author_id" required>
                <option value="">Select an author</option>
                <?php while ($author = mysqli_fetch_assoc($result_users)): ?>
                    <option value="<?php echo $author['author_id']; ?>">
                        <?php echo htmlspecialchars($author['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
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
        
        <br>

        <div class="top-bar">
        <button><a href="admindashboard.php" class="home-btn">Back</a></button>
        </div>
    </form>
</body>
</html>