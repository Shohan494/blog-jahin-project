<?php
session_start();
include "../../model/database.php"; // Adjust path if needed

// Simulated login for testing (replace with real login logic)
if (!isset($_SESSION['role'])) {
    echo "Access Denied. Please log in.";
    exit;
}

// Handle delete
if (isset($_GET['delete_author']) && $_SESSION['role'] === 'admin') {
    $author_id = (int) $_GET['delete_author'];
    $stmt = $conn->prepare("DELETE FROM authors WHERE author_id = ?");
    $stmt->bind_param("i", $author_id);
    $stmt->execute();
    header("Location: authors_info.php?message=Author+deleted+successfully");
    exit;
}

// Handle add/update author
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $author_id = $_POST['author_id'] ?? null;

    if ($author_id) {
        $stmt = $conn->prepare("UPDATE authors SET name=?, email=?, bio=? WHERE author_id=?");
        $stmt->bind_param("sssi", $name, $email, $bio, $author_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO authors (name, email, bio, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $email, $bio);
    }
    $stmt->execute();
    header("Location: authors_info.php?message=Author+saved+successfully");
    exit;
}

// Fetch authors
$authors = $conn->query("SELECT * FROM authors ORDER BY created_at DESC");

// Edit mode
$editing = false;
$edit_data = ['author_id' => '', 'name' => '', 'email' => '', 'bio' => ''];
if (isset($_GET['edit']) && $_SESSION['role'] === 'admin') {
    $editing = true;
    $stmt = $conn->prepare("SELECT * FROM authors WHERE author_id = ?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_data = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Author Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        h1,
        h2 {
            text-align: center;
            color: #333;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .btn {
            background-color: #007BFF;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .actions {
            display: flex;
            gap: 5px;
        }

        .actions a {
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .edit {
            background-color: #28a745;
        }

        .delete {
            background-color: #dc3545;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .message {
            padding: 10px;
            margin: 15px 0;
            background: #d4edda;
            color: #155724;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>

    <h1>Author Management</h1>

    <div class="top-bar">
        <div><strong>User:</strong> <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>)</div>
        <a href="post.php" class="btn">Home</a>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="message"><?= htmlspecialchars($_GET['message']) ?></div>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="form-container">
            <h2><?= $editing ? 'Edit Author' : 'Add Author' ?></h2>
            <form method="POST">
                <input type="hidden" name="author_id" value="<?= htmlspecialchars($edit_data['author_id']) ?>">
                <label>Name:</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($edit_data['name']) ?>">
                <label>Email:</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($edit_data['email']) ?>">
                <label>Bio:</label>
                <textarea name="bio"><?= htmlspecialchars($edit_data['bio']) ?></textarea>
                <input class="btn" type="submit" value="<?= $editing ? 'Update Author' : 'Add Author' ?>">
            </form>
        </div>
    <?php endif; ?>

    <h2>All Authors</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Bio</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $authors->fetch_assoc()): ?>
            <tr>
                <td><?= $row['author_id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['bio']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td class="actions">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a class="edit" href="?edit=<?= $row['author_id'] ?>">Edit</a>
                        <a class="delete" href="?delete_author=<?= $row['author_id'] ?>"
                            onclick="return confirm('Delete this author?')">Delete</a>
                    <?php else: ?>
                        View Only
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>

</html>