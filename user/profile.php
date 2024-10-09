<?php
// Database connection using PDO (you can replace with your actual credentials)
$dsn = "mysql:host=localhost;dbname=kpie";
$username = "root";
$password = "";

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch user data based on a user ID passed via GET (for example ?id=1)
$user_id = $_GET['id'] ?? 1;  // For demonstration purposes, fallback to id=1

// Get the current user details
$statement = $conn->prepare("SELECT * FROM users WHERE user_id = :id");
$statement->bindParam(':id', $user_id);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found!");
}

// Handle form submission
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    if (!password_verify($current_password, $user['hash'])) {
        $error_message = "Current password is incorrect.";
    } else {
        // Check if the new password matches the confirmation password
        if ($new_password !== $confirm_password) {
            $error_message = "New password and confirmation password do not match.";
        } else {
            // Hash the new password if it's provided
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update user information in the database
            $update_statement = $conn->prepare("UPDATE users SET name = :name, email = :email, hash = :hash WHERE user_id = :id");
            $update_statement->bindParam(':name', $name);
            $update_statement->bindParam(':email', $email);
            $update_statement->bindParam(':hash', $hashed_password);
            $update_statement->bindParam(':id', $user_id);

            if ($update_statement->execute()) {
                // Redirect to home page after successful update
                header("Location: user_home.php"); // Replace 'home.php' with your actual home page
                exit(); // Make sure no further code is executed after redirect
            } else {
                $error_message = "Error updating profile. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 20px auto;
            padding: 10px;
            background-color: #f4f4f4;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 15px;
            background-color: #009688;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #00796b;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h2>Edit Profile</h2>

<?php if ($error_message): ?>
    <p class="error-message"><?php echo $error_message; ?></p>
<?php endif; ?>

<form action="" method="post">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>

    <div class="form-group">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>
    </div>

    <div class="form-group">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password">
    </div>

    <div class="form-group">
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password">
    </div>

    <button type="submit">Update Profile</button>
</form>

</body>
</html>
