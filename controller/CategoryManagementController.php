<?php
session_start();
include "../../model/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Initialize variables
$message = '';
$editing = false;
$edit_category = null;

// Handle delete request
if (isset($_GET['delete_category'])) {
    $category_id = (int)$_GET['delete_category'];
    $sql = "DELETE FROM categories WHERE category_id = $category_id";
    if (mysqli_query($conn, $sql)) {
        $message = "Category deleted successfully.";
    } else {
        $message = "Error deleting category: " . mysqli_error($conn);
    }
}

// Handle create/update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));

    if (!$name) {
        $message = "Category name is required.";
    } else {
        if (isset($_POST['category_id']) && $_POST['category_id']) {
            // Update existing category
            $category_id = (int)$_POST['category_id'];
            $sql = "UPDATE categories SET name = '$name', description = '$description', created_at = NOW() WHERE category_id = $category_id";
            if (mysqli_query($conn, $sql)) {
                $message = "Category updated successfully.";
            } else {
                $message = "Error updating category: " . mysqli_error($conn);
            }
        } else {
            // Create new category
            $sql = "INSERT INTO categories (name, description, created_at) VALUES ('$name', '$description', NOW())";
            if (mysqli_query($conn, $sql)) {
                $message = "Category created successfully.";
            } else {
                $message = "Error creating category: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch category for editing
if (isset($_GET['edit_category'])) {
    $editing = true;
    $edit_category_id = (int)$_GET['edit_category'];
    $sql = "SELECT * FROM categories WHERE category_id = $edit_category_id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_category = mysqli_fetch_assoc($result);
    }
    mysqli_free_result($result);
}

// Fetch all categories
$sql = "SELECT * FROM categories ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

mysqli_close($conn);
?>