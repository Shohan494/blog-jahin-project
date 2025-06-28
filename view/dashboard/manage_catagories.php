<?php
session_start();
include "../../model/database.php"; // Your DB connection file

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}

// Handle Add Category`
if (isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    if ($name !== '') {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Category added successfully.";
        } else {
            $_SESSION['error'] = "Error adding category: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Category name cannot be empty.";
    }
    header("Location: manage_categories.php");
    exit();
}

// Handle Delete Category
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Category deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting category: " . $conn->error;
    }
    header("Location: manage_categories.php");
    exit();
}

// Handle Edit Category
if (isset($_POST['edit_category'])) {
    $id = (int) $_POST['id'];
    $name = trim($_POST['name']);
    if ($name !== '') {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Category updated successfully.";
        } else {
            $_SESSION['error'] = "Error updating category: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Category name cannot be empty.";
    }
    header("Location: manage_categories.php");
    exit();
}

// Fetch all categories
$result = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Manage Categories</title>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  



        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="brand">AdminPanel</div>
            <nav>
                <a href="admindashboard.php" class="active">Dashboard</a>
                <a href="manage_categories.php">Categories Management</a>
                <a href="new_post.php">Create New Post</a>
                <a href="see_post.php">See All Posts</a>
                <a href="authors_info.php">Authors Info</a>
                <a href="newsletter.php">Newsletter</a>
                <a href="tags.php">Tags</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main">
            <!-- Top Bar -->
            <div class="topbar">
                <button title="Notifications">🔔</button>
                <div class="profile" id="profileBtn">
                    <img src="../image/jahin.jpg" alt="Profile" class="avatar">
                    <div class="dropdown" id="profileDropdown">
                        <a href="myaccount.php">My Profile</a>
                        <a href="settings.php">Settings</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </div>


</body>
</html>
<style>
  body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
  table { border-collapse: collapse; width: 50%; margin-bottom: 20px; }
  th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
  th { background: #007bff; color: white; }
  tr:nth-child(even) { background-color: #f2f2f2; }
  .message { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
  .error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
  input[type="text"] { padding: 6px; width: 250px; }
  input[type="submit"], button { padding: 6px 12px; border: none; background: #007bff; color: white; border-radius: 4px; cursor: pointer; }
  button.delete { background: #dc3545; }
  form.inline { display: inline; }
</style>
</head>
<body>

<h1>Category Manager</h1>

<?php if (isset($_SESSION['message'])): ?>
      <div class="message"><?= $_SESSION['message'] ?></div>
      <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
      <div class="error"><?= $_SESSION['error'] ?></div>
      <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Add New Category Form -->
<form method="POST" action="manage_categories.php">
    <input type="text" name="name" placeholder="New category name" required>
    <input type="submit" name="add_category" value="Add Category">
</form>

<!-- Categories Table -->
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Actions</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td>
          <!-- Inline Edit Form -->
          <form method="POST" action="manage_categories.php" class="inline">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
            <input type="submit" name="edit_category" value="Update">
          </form>
        </td>
        <td>
          <a href="manage_categories.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this category?');">
            <button class="delete">Delete</button>
          </a>
        </td>
      </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
