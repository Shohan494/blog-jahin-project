<?php
session_start();
include "../../model/database.php";

// Redirect to login if user_id is not set
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int) $_SESSION['user_id'];
$username = isset($_SESSION['username']) ? mysqli_real_escape_string($conn, $_SESSION['username']) : '';

// Initialize profile data array with default values
$profileData = [
    'username' => '',
    'email' => '',
    'status' => 'Active',
    'profile_pic' => null,
    'updated_at' => '',
];

// Fetch existing profile data from user_profile using pro_id
$sql = "SELECT username, email, status, profile_pic, updated_at FROM user_profile WHERE pro_id = $userId";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $profileData = mysqli_fetch_assoc($result);
}
mysqli_free_result($result);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $status = mysqli_real_escape_string($conn, $_POST['status'] ?? 'Active');

    if (!$email) {
        $message = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Check if username exists in 'users' table (foreign key check)
        $sqlUserCheck = "SELECT COUNT(*) AS count FROM users WHERE username = '$username'";
        $resultUserCheck = mysqli_query($conn, $sqlUserCheck);
        $userExists = false;
        if ($resultUserCheck && $rowUserCheck = mysqli_fetch_assoc($resultUserCheck)) {
            $userExists = ($rowUserCheck['count'] > 0);
        }
        mysqli_free_result($resultUserCheck);

        if (!$userExists) {
            $message = "The username '$username' does not exist in the users table.";
        } else {
            $profilePicFileName = $profileData['profile_pic'];

            // Handle file upload if provided
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $fileExt = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));
                if (!in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $message = "Only JPG, PNG, and GIF files are allowed.";
                } elseif ($_FILES['picture']['size'] > 2 * 1024 * 1024) { // Limit to 2MB
                    $message = "File size exceeds 2MB limit.";
                } else {
                    $newFileName = uniqid('profile_', true) . '.' . $fileExt;
                    $uploadDir = __DIR__ . '/blog/view/image/';
                    $uploadPath = $uploadDir . $newFileName;

                    // Ensure directory exists and is writable
                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0755, true)) {
                            $message = "Failed to create upload directory.";
                        }
                    } elseif (!is_writable($uploadDir)) {
                        $message = "Upload directory is not writable.";
                    } else {
                        if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadPath)) {
                            // Delete old image if it exists
                            if ($profilePicFileName && file_exists($uploadDir . $profilePicFileName)) {
                                unlink($uploadDir . $profilePicFileName);
                            }
                            $profilePicFileName = $newFileName;
                        } else {
                            $message = "Failed to upload image.";
                        }
                    }
                }
            } elseif (isset($_FILES['picture']) && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE) {
                $message = "Error uploading file: " . $_FILES['picture']['error'];
            }

            // Proceed only if no error message
            if (!$message) {
                // Check if profile exists
                $sqlCheck = "SELECT COUNT(*) AS count FROM user_profile WHERE pro_id = $userId";
                $resultCheck = mysqli_query($conn, $sqlCheck);
                $exists = $resultCheck && mysqli_fetch_assoc($resultCheck)['count'] > 0;
                mysqli_free_result($resultCheck);

                if ($exists) {
                    // Update existing profile
                    $sqlUpdate = "UPDATE user_profile SET email = '$email', status = '$status', profile_pic = " . 
                                 ($profilePicFileName ? "'$profilePicFileName'" : "NULL") . ", updated_at = NOW() WHERE pro_id = $userId";
                    $result = mysqli_query($conn, $sqlUpdate);
                } else {
                    // Insert new profile
                    $sqlInsert = "INSERT INTO user_profile (pro_id, username, email, status, profile_pic, updated_at) VALUES " .
                                 "($userId, '$username', '$email', '$status', " . 
                                 ($profilePicFileName ? "'$profilePicFileName'" : "NULL") . ", NOW())";
                    $result = mysqli_query($conn, $sqlInsert);
                }

                if ($result) {
                    $message = "Profile updated successfully.";
                    // Reload updated profile data
                    $sqlReload = "SELECT username, email, status, profile_pic, updated_at FROM user_profile WHERE pro_id = $userId";
                    $resReload = mysqli_query($conn, $sqlReload);
                    if ($resReload && mysqli_num_rows($resReload) > 0) {
                        $profileData = mysqli_fetch_assoc($resReload);
                    }
                    mysqli_free_result($resReload);
                } else {
                    $message = "Failed to save profile data: " . mysqli_error($conn);
                }
            }
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px 20px;
            background-color: #0366d6;
            display: flex;
            justify-content: center;
            min-height: 100vh;
            box-sizing: border-box;
            position: relative;
        }
        .panel {
            background: white;
            padding: 60px;
            border-radius: 12px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            min-height: 650px;
        }
        h1 {
            margin-top: 0;
            margin-bottom: 25px;
            font-weight: 700;
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 600;
        }
        input, select {
            width: 100%;
            background: white;
            border: 1.8px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
            padding: 12px 15px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        input:focus, select:focus {
            border-color: #024a9c;
            outline: none;
        }
        button {
            padding: 12px 25px;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            background-color: #0366d6;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-right: 10px;
        }
        button:hover {
            background-color: #024a9c;
        }
        img.profile-pic {
            max-width: 150px;
            max-height: 150px;
            margin: 15px auto 30px;
            border-radius: 50%;
            border: 3px solid #0366d6;
            object-fit: cover;
            display: block;
        }
        .message {
            font-weight: 700;
            margin-bottom: 15px;
            color: green;
            text-align: center;
        }
        .error {
            font-weight: 700;
            margin-bottom: 15px;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="panel">
        <h1>Update Your Profile</h1>

        <?php if ($message): ?>
            <div class="<?php echo strpos($message, 'successfully') !== false ? 'message' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php 
        $profilePicPath = 'blog/view/image/default-avatar.png';
        if ($profileData['profile_pic'] && file_exists(__DIR__ . '/blog/view/image/' . $profileData['profile_pic'])) {
            $profilePicPath = 'blog/view/image/' . $profileData['profile_pic'] . '?v=' . filemtime(__DIR__ . '/blog/view/image/' . $profileData['profile_pic']);
        }
        ?>
        <img src="<?php echo htmlspecialchars($profilePicPath); ?>" alt="Profile Picture" class="profile-pic">

        <form method="POST" enctype="multipart/form-data">
            <label for="picture">Profile Picture (JPG, PNG, GIF):</label>
            <input type="file" name="picture" id="picture">

            <label for="email">Email*:</label>
            <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($profileData['email']); ?>">

            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="Active" <?php if ($profileData['status'] == 'Active') echo 'selected'; ?>>Active</option>
                <option value="Inactive" <?php if ($profileData['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
            </select>

            <button type="submit">Save Profile</button>
            <button type="button" onclick="clearFields()">Clear All</button>
        </form>
    </div>

    <script>
        function clearFields() {
            document.getElementById('picture').value = '';
            document.getElementById('email').value = '';
            document.getElementById('status').selectedIndex = 0;
        }
    </script>
</body>
</html>