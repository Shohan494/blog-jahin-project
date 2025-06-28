<?php
session_start();
include "../../model/database.php";

// Delete comment by comment_id
if (isset($_GET['delete_comment'])) {
    $comment_id = (int) $_GET['delete_comment'];

    $stmt = $conn->prepare("DELETE FROM comments WHERE comment_id = ?");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    header("Location: manage_comments.php?message=Comment+deleted");
    exit();
}

// Update comment content (optional, you can add a form and handler if you want)

// Fetch all comments ordered by created_at DESC
$sql = "SELECT * FROM comments ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Comments</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #007bff; color: white; }
        .actions a { padding: 5px 10px; margin-right: 5px; text-decoration: none; border-radius: 3px; color: white; }
        .delete { background: #dc3545; }
        .message { background: #d4edda; padding: 10px; margin-bottom: 10px; color: #155724; }
    </style>
</head>
<body>

<h2>All Comments</h2>

<?php if (isset($_GET['message'])): ?>
        <div class="message"><?= htmlspecialchars($_GET['message']) ?></div>
<?php endif; ?>

<?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Post Title</th>
                <th>Author Name</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>

            <?php
            while ($row = $result->fetch_assoc()):
                // Get post title
                $post_stmt = $conn->prepare("SELECT title FROM posts WHERE post_id = ?");
                $post_stmt->bind_param("i", $row['post_id']);
                $post_stmt->execute();
                $post_res = $post_stmt->get_result();
                $post_title = $post_res->fetch_assoc()['title'] ?? 'Unknown Post';

                // Get author username
                $author_stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
                $author_stmt->bind_param("i", $row['author_id']);
                $author_stmt->execute();
                $author_res = $author_stmt->get_result();
                $author_name = $author_res->fetch_assoc()['username'] ?? 'Unknown User';
                ?>
                    <tr>
                        <td><?= $row['comment_id'] ?></td>
                        <td><?= htmlspecialchars($post_title) ?></td>
                        <td><?= htmlspecialchars($author_name) ?></td>
                        <td><?= htmlspecialchars($row['content']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td class="actions">
                            <a class="delete" href="?delete_comment=<?= $row['comment_id'] ?>" onclick="return confirm('Delete this comment?')">Delete</a>
                        </td>
                    </tr>
                <?php
                $post_stmt->close();
                $author_stmt->close();
            endwhile;
            ?>
        </table>
<?php else: ?>
        <p>No comments found.</p>
<?php endif; ?>

</body>
</html>
