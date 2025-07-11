


<?php
require_once '../../controller/NewsLetterController.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Subscribers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .nav-link {
            background-color: #007BFF;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .nav-link:hover {
            background-color: #0056b3;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="email"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .unsubscribe-btn {
            background-color: #dc3545;
        }
        .unsubscribe-btn:hover {
            background-color: #c82333;
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
        .filter-form {
            margin-bottom: 20px;
            text-align: center;
        }
        .message {
            color: green;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>Manage Subscribers</h1>
    <div class="top-bar">
        <a href="admindashboard.php" class="nav-link">Back to Dashboard</a>
        <a href="logout.php" class="nav-link">Logout</a>
    </div>

    <?php if ($message): ?>
        <p class="<?php echo strpos($message, 'successfully') !== false || strpos($message, 'sent') !== false ? 'message' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <h2>Add Subscriber</h2>
    <form method="POST" action="">
        <input type="hidden" name="add_subscriber" value="1">
        <div class="form-group">
            <label for="author_id">Select Author (Optional):</label>
            <select id="author_id" name="author_id" onchange="fillEmail(this)">
                <option value="">-- Select an Author --</option>
                <?php while ($user = mysqli_fetch_assoc($result_users)): ?>
                    <option value="<?php echo $user['user_id']; ?>" data-email="<?php echo htmlspecialchars($user['email']); ?>">
                        <?php echo htmlspecialchars($user['username']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <button type="submit">Add Subscriber</button>
    </form>

    <h2>Subscribers</h2>
    <div class="filter-form">
        <form method="GET" action="">
            <label for="status">Filter by Status:</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="confirmed" <?php echo $status_filter == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                <option value="unsubscribed" <?php echo $status_filter == 'unsubscribed' ? 'selected' : ''; ?>>Unsubscribed</option>
            </select>
        </form>
    </div>

    <?php if ($result_subscribers && mysqli_num_rows($result_subscribers) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            <?php while ($subscriber = mysqli_fetch_assoc($result_subscribers)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($subscriber['id']); ?></td>
                    <td><?php echo htmlspecialchars($subscriber['email']); ?></td>
                    <td><?php echo htmlspecialchars($subscriber['status']); ?></td>
                    <td><?php echo htmlspecialchars($subscriber['created_at']); ?></td>
                    <td>
                        <?php if ($subscriber['status'] != 'unsubscribed'): ?>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="unsubscribe_id" value="<?php echo $subscriber['id']; ?>">
                                <button type="submit" class="unsubscribe-btn">Unsubscribe</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No subscribers found.</p>
    <?php endif; ?>

    <script>
        function fillEmail(select) {
            const emailInput = document.getElementById('email');
            const selectedOption = select.options[select.selectedIndex];
            emailInput.value = selectedOption.getAttribute('data-email') || '';
        }
    </script>
</body>
</html>