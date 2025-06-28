<?php
session_start();
include "../../model/database.php"; // Adjust path if needed

// Check admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
header("Location: login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete_author'])) {
    $author_id = (int) $_GET['delete_author'];
    $sql = "DELETE FROM authors WHERE author_id = $author_id";
    mysqli_query($conn, $sql);
    header("Location: authors_info.php?message=Author+deleted+successfully");
    exit;
}

// Handle add/update author
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $author_id = isset($_POST['author_id']) ? (int) $_POST['author_id'] : null;

    if ($author_id) {
        $sql = "UPDATE authors SET name='$name', email='$email', bio='$bio' WHERE author_id=$author_id";
    } else {
        $sql = "INSERT INTO authors (name, email, bio, created_at) VALUES ('$name', '$email', '$bio', NOW())";
    }
    mysqli_query($conn, $sql);
    header("Location: authors_info.php?message=Author+saved+successfully");
    exit;
}

// Fetch authors
$sql = "SELECT * FROM authors ORDER BY created_at DESC";
$authors = mysqli_query($conn, $sql);

// Edit mode
$editing = false;
$edit_data = ['author_id' => '', 'name' => '', 'email' => '', 'bio' => ''];
if (isset($_GET['edit'])) {
    $editing = true;
    $edit_id = (int) $_GET['edit'];
    $sql = "SELECT * FROM authors WHERE author_id = $edit_id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_data = mysqli_fetch_assoc($result);
    }
}

mysqli_close($conn);
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
        h1, h2 {
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
        th, td {
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
        <div><strong>User:</strong> <?php echo htmlspecialchars($_SESSION['username'] ?? 'Unknown'); ?> (<?php echo htmlspecialchars($_SESSION['role'] ?? 'N/A'); ?>)</div>
        <a href="admindashboard.php" class="btn">Back</a>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="message"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <div class="form-container">
        <h2><?php echo $editing ? 'Edit Author' : 'Add Author'; ?></h2>
        <form method="POST">
            <input type="hidden" name="author_id" value="<?php echo htmlspecialchars($edit_data['author_id']); ?>">
            <label>Name:</label>
            <input type="text" name="name" required value="<?php echo htmlspecialchars($edit_data['name']); ?>">
            <label>Email:</label>
            <input type="email" name="email" required value="<?php echo htmlspecialchars($edit_data['email']); ?>">
            <label>Bio:</label>
            <textarea name="bio"><?php echo htmlspecialchars($edit_data['bio']); ?></textarea>
            <input class="btn" type="submit" value="<?php echo $editing ? 'Update Author' : 'Add Author'; ?>">
        </form>
    </div>

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
        <?php while ($row = mysqli_fetch_assoc($authors)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['author_id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['bio']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td class="actions">
                    <a class="edit" href="?edit=<?php echo $row['author_id']; ?>">Edit</a>
                    <a class="delete" href="?delete_author=<?php echo $row['author_id']; ?>" onclick="return confirm('Delete this author?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>