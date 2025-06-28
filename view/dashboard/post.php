
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #0366d6;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 22px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover,
        .navbar a.active {
            background-color: #024a9c;
        }

        @media screen and (max-width: 600px) {
            .navbar a {
                float: none;
                text-align: left;
                padding: 12px 20px;
                border-bottom: 1px solid #024a9c;
            }
        }

        .content {
            padding: 20px;
        }

        h2 {
            color: #0366d6;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <a href="admindashboard.php" class="<?= $current == 'admindashboard.php' ? 'active' : '' ?>">Home</a>
        <a href="see_post.php" class="<?= $current == 'user_posts.php' ? 'active' : '' ?>">See User Posts</a>
        <a href="comments.php" class="<?= $current == 'comments.php' ? 'active' : '' ?>">Comments</a>

        <!-- NEW: Category Manager -->
        <a href="manage_catagories.php" class="<?= $current == 'manage_catagories.php' ? 'active' : '' ?>">Manage Categories</a>

        <!-- NEW: Featured Posts -->
        <a href="featured_posts.php" class="<?= $current == 'featured_posts.php' ? 'active' : '' ?>">Featured Posts</a>

        <!-- NEW: Archive View -->
        <a href="archive_view.php" class="<?= $current == 'archive_view.php' ? 'active' : '' ?>">Archives</a>

        <a href="logout.php" style="float:right;">Logout</a>
    </div>

    <div class="content">
        <h2>Welcome to the Admin Dashboard</h2>
        <p>Select a section from the navigation above to manage blog content.</p>
    </div>

</body>

</html>
