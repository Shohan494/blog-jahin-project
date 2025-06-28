<?php
session_start();
include "../../model/database.php"; // your mysqli $conn connection

// For example, replace this with your logged-in user ID
$userId = 1;

// Initialize profile data array
$profileData = [
    'username' => '',
    'email' => '',
    'status' => 'Active',
    'profile_pic' => null,
    'updated_at' => '',
];

// Fetch existing profile data from user_profile using pro_id (assuming id renamed to pro_id)
$sql = "SELECT * FROM user_profile WHERE pro_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($result && mysqli_num_rows($result) > 0) {
    $profileData = mysqli_fetch_assoc($result);
}
mysqli_stmt_close($stmt);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    print_r($_POST);
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $status = $_POST['status'] ?? 'Active';

    if (!$username || !$email) {
        $message = "Username and Email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Check if username exists in 'users' table (foreign key check)
        $sqlUserCheck = "SELECT COUNT(*) AS count FROM users WHERE username = ?";
        $stmtUserCheck = mysqli_prepare($conn, $sqlUserCheck);
        mysqli_stmt_bind_param($stmtUserCheck, "s", $username);
        mysqli_stmt_execute($stmtUserCheck);
        $resultUserCheck = mysqli_stmt_get_result($stmtUserCheck);
        $userExists = false;
        if ($rowUserCheck = mysqli_fetch_assoc($resultUserCheck)) {
            $userExists = ($rowUserCheck['count'] > 0);
        }
        mysqli_stmt_close($stmtUserCheck);

        if (!$userExists) {
            $message = "The username '$username' does not exist in the users table. Please use an existing username.";
        } else {
            $profilePicFileName = $profileData['profile_pic']; // existing profile pic filename

            // Handle file upload if provided
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['picture']['tmp_name'];
                $fileName = basename($_FILES['picture']['name']);
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($fileExt, $allowedExts)) {
                    $message = "Only JPG, PNG, and GIF files are allowed.";
                } else {
                    $newFileName = uniqid('profile_', true) . '.' . $fileExt;
                    $uploadDir = __DIR__ . '/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $destination = $uploadDir . $newFileName;

                    if (move_uploaded_file($tmpName, $destination)) {
                        // Delete old image file if exists
                        if ($profilePicFileName && file_exists($uploadDir . $profilePicFileName)) {
                            unlink($uploadDir . $profilePicFileName);
                        }
                        $profilePicFileName = $newFileName;
                    } else {
                        $message = "Failed to upload image.";
                    }
                }
            }

            // Proceed only if no error message from file upload
            if (!$message) {
                // Check if profile exists (using pro_id)
                $sqlCheck = "SELECT COUNT(*) AS count FROM user_profile WHERE pro_id = ?";
                $stmtCheck = mysqli_prepare($conn, $sqlCheck);
                mysqli_stmt_bind_param($stmtCheck, "i", $userId);
                mysqli_stmt_execute($stmtCheck);
                $resultCheck = mysqli_stmt_get_result($stmtCheck);
                $exists = false;
                if ($row = mysqli_fetch_assoc($resultCheck)) {
                    $exists = ($row['count'] > 0);
                }
                mysqli_stmt_close($stmtCheck);

                if ($exists) {
                    // Update existing profile
                    $sqlUpdate = "UPDATE user_profile SET username = ?, email = ?, status = ?, profile_pic = ?, updated_at = NOW() WHERE pro_id = ?";
                    $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
                    mysqli_stmt_bind_param($stmtUpdate, "ssssi", $username, $email, $status, $profilePicFileName, $userId);
                    $result = mysqli_stmt_execute($stmtUpdate);
                    mysqli_stmt_close($stmtUpdate);
                } else {
                    // Insert new profile
                    $sqlInsert = "INSERT INTO user_profile (pro_id, username, email, status, profile_pic, updated_at) VALUES (?, ?, ?, ?, ?, NOW())";
                    $stmtInsert = mysqli_prepare($conn, $sqlInsert);
                    mysqli_stmt_bind_param($stmtInsert, "issss", $userId, $username, $email, $status, $profilePicFileName);
                    $result = mysqli_stmt_execute($stmtInsert);
                    mysqli_stmt_close($stmtInsert);
                }

                if ($result) {
                    $message = "Profile updated successfully.";

                    // Reload updated profile data
                    $stmtReload = mysqli_prepare($conn, "SELECT * FROM user_profile WHERE pro_id = ?");
                    mysqli_stmt_bind_param($stmtReload, "i", $userId);
                    mysqli_stmt_execute($stmtReload);
                    $resReload = mysqli_stmt_get_result($stmtReload);
                    if ($resReload && mysqli_num_rows($resReload) > 0) {
                        $profileData = mysqli_fetch_assoc($resReload);
                    }
                    mysqli_stmt_close($stmtReload);
                } else {
                    $message = "Failed to save profile data.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Update Profile</title>
    <style>
        /* Your CSS styling */
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
    padding: 160px 60px; /* 160px top & bottom, 60px left & right */
    border-radius: 12px;
    max-width: 600px;
    width: 100%;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
    min-height: 650px; /* or whatever height you want */
        }

        h1 {
            margin-top: 0;
            margin-bottom: 25px;
            font-weight: 700;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 600;
        }

        input,
        select {
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

        input:focus,
        select:focus {
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
            margin: 15px 0 30px;
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

        <?php if ($profileData['profile_pic'] && file_exists(__DIR__ . '/uploads/' . $profileData['profile_pic'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($profileData['profile_pic']); ?>" alt="Profile Picture"
                class="profile-pic" />
        <?php else: ?>
            <p style="text-align:center; font-style: italic; color: #666;">No profile picture uploaded yet.</p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="picture">Profile Picture (JPG, PNG, GIF):</label>
            <input type="file" name="picture" id="picture">

            <label for="username">Username*:</label>
            <input type="text" name="username" id="username" required
                value="<?php echo htmlspecialchars($profileData['username']); ?>">

            <label for="email">Email*:</label>
            <input type="email" name="email" id="email" required
                value="<?php echo htmlspecialchars($profileData['email']); ?>">

            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="Active" <?php if ($profileData['status'] == 'Active')
                    echo 'selected'; ?>>Active</option>
                <option value="Inactive" <?php if ($profileData['status'] == 'Inactive')
                    echo 'selected'; ?>>Inactive
                </option>
            </select>

            <button type="submit">Save Profile</button>
            <button type="button" onclick="clearFields()">Clear ALL</button>
        </form>
    </div>

    <script>
        function clearFields() {
            document.getElementById('picture').value = '';
            document.getElementById('username').value = '';
            document.getElementById('email').value = '';
            document.getElementById('status').selectedIndex = 0;
        }
    </script>
</body>

</html>