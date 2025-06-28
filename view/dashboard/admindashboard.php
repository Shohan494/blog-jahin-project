<?php
session_start();
include "../../model/database.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
        }

        /* Flex container */
        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #24292e;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px 0;
        }

        .sidebar .brand {
            font-size: 1.8rem;
            font-weight: 700;
            padding: 0 20px 30px 20px;
            letter-spacing: 1px;
            border-bottom: 1px solid #444c56;
        }

        .sidebar nav a {
            display: block;
            padding: 15px 30px;
            color: #cfd8dc;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background-color: #0366d6;
            color: white;
        }

        /* Main content wrapper */
        .main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: auto;
        }

        /* Topbar */
        .topbar {
            height: 60px;
            background-color: #ffffff;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0 20px;
            gap: 20px;
        }

        .topbar a,
        .topbar button {
            background: none;
            border: none;
            font-size: 14px;
            text-decoration: none;
            color: #333;
            cursor: pointer;
        }

        .topbar a:hover,
        .topbar button:hover {
            color: #0366d6;
        }

        /* Profile dropdown */
        .profile {
            position: relative;
            cursor: pointer;
        }

        .profile .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #0366d6;
        }

        .profile .dropdown {
            display: none;
            position: absolute;
            top: 45px;
            right: 0;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            z-index: 1001;
            min-width: 150px;
        }

        .profile .dropdown a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
        }

        .profile .dropdown a:hover {
            background-color: #f0f0f0;
        }

        .profile .dropdown.show {
            display: block;
        }

        /* Content */
        .content {
            padding: 30px;
            flex-grow: 1;
        }

        @media (max-width: 700px) {
            .sidebar {
                width: 200px;
            }

            .topbar {
                flex-wrap: wrap;
                height: auto;
                padding: 10px;
                justify-content: space-between;
            }

            .content {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <!-- Flex container -->
    <div class="container">

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

            <!-- Page Content -->
            <div class="content">
                <h1>Welcome to Admin Dashboard</h1>
                <p>This is your blogging platform control panel.</p>
            </div>
        </div>

    </div>

    <!-- JS: Profile Dropdown Toggle -->
    <script>
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');

        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });

        // Close dropdown if clicked outside
        window.addEventListener('click', function (e) {
            if (!profileBtn.contains(e.target)) {
                profileDropdown.classList.remove('show');
            }
        });
    </script>

</body>

</html>