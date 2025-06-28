<?php
// user_posts.php

session_start();
include "../../model/database.php";

// Restrict access to admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch recipes with author info
$sql = "
SELECT 
  r.recipe_id,
  r.title,
  r.created_at,
  r.image_url,
  u.username
FROM recipes r
JOIN users u ON r.user_id = u.user_id
ORDER BY r.created_at DESC
";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin – User Posts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f8f9fa;
        }

        h1 {
            color: #0366d6;
            margin-bottom: 30px;
        }

        .post-card {
            background: #fff;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .post-card img {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 20px;
        }

        .post-info {
            flex: 1;
        }

        .post-info h3 {
            margin: 0;
        }

        .post-meta {
            font-size: 0.9em;
            color: #555;
            margin-bottom: 10px;
        }

        .view-link {
            background-color: #007bff;
            color: #fff;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .view-link:hover {
            background-color: #0069d9;
        }
    </style>
</head>

<body>
    <h1>All User Posts</h1>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="post-card">
                <img src="<?php echo htmlspecialchars($row[''] ?: ''); ?>" alt="Recipe Image">
                <div class="post-info">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <div class="post-meta">
                        <strong>Author:</strong> <?php echo htmlspecialchars($row['username']); ?><br>
                        <strong>Date:</strong> <?php echo htmlspecialchars($row['created_at']); ?>
                    </div>
                    <a class="view-link" href="view_recipe.php?id=<?php echo urlencode($row['recipe_id']); ?>">
                        View Recipe
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>
</body>

</html>