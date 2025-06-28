<?php
session_start();
include "../../model/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
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