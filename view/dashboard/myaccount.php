<?php
session_start();
include "../../model/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int) $_SESSION['user_id'];

// Initialize profileData with defaults
$profileData = [
    'username' => 'Not set',
    'email' => 'Not set',
    'status' => 'Not set',
    'profile_pic' => '',
    'updated_at' => 'Not set'
];

// Fetch user data from users table
$sqlUser = "SELECT username, email FROM users WHERE user_id = $userId LIMIT 1";
$resultUser = mysqli_query($conn, $sqlUser);
if ($resultUser && mysqli_num_rows($resultUser) > 0) {
    $userRow = mysqli_fetch_assoc($resultUser);
    $profileData['username'] = $userRow['username'];
    $profileData['email'] = $userRow['email'];
}
mysqli_free_result($resultUser);

// Fetch profile data from account_profile table
$sqlProfile = "SELECT profile_pic, status, updated_at FROM user_profile WHERE pro_id = $userId LIMIT 1";
$resultProfile = mysqli_query($conn, $sqlProfile);
if ($resultProfile && mysqli_num_rows($resultProfile) > 0) {
    $profileRow = mysqli_fetch_assoc($resultProfile);
    $profileData['status'] = $profileRow['status'] ?? 'Not set';
    $profileData['profile_pic'] = $profileRow['profile_pic'] ?? '';
    $profileData['updated_at'] = $profileRow['updated_at'] ?? 'Not set';
}
mysqli_free_result($resultProfile);

// Handle profile picture path
$uploadDir = "blog/view/image/"; // Adjust path relative to this PHP file
$defaultPic = "default-avatar.png";
$profilePicPath = $uploadDir . $defaultPic;

if (!empty($profileData['profile_pic'])) {
    $fullPath = __DIR__ . "/" . $uploadDir . $profileData['profile_pic'];
    if (file_exists($fullPath)) {
        // Append filemtime to bust cache when image updates
        $profilePicPath = $uploadDir . $profileData['profile_pic'] . '?v=' . filemtime($fullPath);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f9f9f9;
            position: relative;
            min-height: 100vh;
            margin: 0;
        }
        .back-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 8px 15px;
            font-size: 16px;
            cursor: pointer;
            background-color: #0366d6;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            z-index: 1000;
        }
        .back-button:hover {
            background-color: #024a9c;
        }
        .profile-container {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
            text-align: center;
            padding-top: 60px;
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 3px solid #0366d6;
        }
        .profile-info p {
            font-size: 18px;
            margin: 8px 0;
            text-align: left;
        }
        .profile-info strong {
            color: #0366d6;
        }
    </style>
</head>
<body>
    <button class="back-button" onclick="location.href='admindashboard.php'">Back</button>

    <div class="profile-container">
        <h1>My Account</h1>

        <img src="<?php echo htmlspecialchars($profilePicPath); ?>" alt="Profile Picture" class="profile-pic">

        <div class="profile-info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($profileData['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($profileData['email']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($profileData['status']); ?></p>
            <p><em>Last updated: <?php echo htmlspecialchars($profileData['updated_at']); ?></em></p>
        </div>
    </div>
</body>
</html>