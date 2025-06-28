<?php
require_once '../../controller/CategoryManagementController.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <style>
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
        th, td {
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
        .category-form {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }
        .category-form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        .category-form input[type="text"],
        .category-form textarea {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .category-form textarea {
            resize: vertical;
            min-height: 100px;
        }
        .category-form input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            margin-top: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .category-form input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Manage Categories</h1>

    <div class="top-bar">
        <a href="admindashboard.php" class="home-btn">Back</a>
    </div>

    <?php if ($message): ?>
        <div class="<?php echo strpos($message, 'successfully') !== false ? 'message' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="category-form">
        <h2><?php echo $editing ? 'Edit Category' : 'Add New Category'; ?></h2>
        <form method="POST" action="">
            <?php if ($editing): ?>
                <input type="hidden" name="category_id" value="<?php echo $edit_category['category_id']; ?>">
            <?php endif; ?>
            <label for="name">Category Name*:</label>
            <input type="text" id="name" name="name" required value="<?php echo $editing ? htmlspecialchars($edit_category['name']) : ''; ?>">
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo $editing ? htmlspecialchars($edit_category['description']) : ''; ?></textarea>
            <input type="submit" value="<?php echo $editing ? 'Update Category' : 'Add Category'; ?>">
        </form>
    </div>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['category_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td class="actions">
                        <a class="edit" href="?edit_category=<?php echo $row['category_id']; ?>">Edit</a>
                        <a class="delete" href="?delete_category=<?php echo $row['category_id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No categories found.</p>
    <?php endif; ?>
</body>
</html>