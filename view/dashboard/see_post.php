

<?php
require_once '../../controller/SeePostController.php';
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Blog Posts</title>
    <style>
        /* Your previous styles here (omitted for brevity) */
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        h1 {
            text-align: center;
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

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .actions a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            font-size: 14px;
            display: inline-block;
        }

        .edit {
            background-color: #28a745;
        }

        .copy {
            background-color: #17a2b8;
        }

        .delete {
            background-color: #dc3545;
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

        .edit-form {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .edit-form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .edit-form input[type="text"],
        .edit-form textarea,
        .edit-form select {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .edit-form textarea {
            resize: vertical;
            min-height: 100px;
        }

        .edit-form input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            margin-top: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .edit-form input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

    <h1>All Blog Posts</h1>

    <div class="top-bar">
        <a href="admindashboard.php" class="home-btn">Back</a>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="message"><?= htmlspecialchars($_GET['message']) ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($editing && $edit_post): ?>
        <div class="edit-form">
            <h2>Edit Post #<?= $edit_post['post_id'] ?></h2>
            <form method="POST" action="see_post.php">
                <input type="hidden" name="post_id" value="<?= $edit_post['post_id'] ?>">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required value="<?= htmlspecialchars($edit_post['title']) ?>">

                <label for="content">Content</label>
                <textarea id="content" name="content" required><?= htmlspecialchars($edit_post['content']) ?></textarea>

                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="draft" <?= $edit_post['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $edit_post['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                </select>

                <input type="submit" value="Update Post">
            </form>
        </div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Author ID</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['post_id']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= $row['content'] ?></td> <!-- assuming safe -->
                    <td><?= htmlspecialchars($row['author_id']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td class="actions">
                    <a class="edit" href="view_post.php?post_id=<?= $row['post_id'] ?>">View</a>
                        <a class="edit" href="?edit_post=<?= $row['post_id'] ?>">Edit</a>
                        <a class="delete" href="?post_id=<?= $row['post_id'] ?>&author_id=<?= $row['author_id'] ?>"
                            onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>

</body>

</html>