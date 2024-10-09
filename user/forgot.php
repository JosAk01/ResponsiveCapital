<?php
session_start();
include('../includes/db.php');

// Handle form submission for forgot password
$error_message = '';
$success_message = '';
if (isset($_POST['submit'])) {
    // Check if email field is empty
    if (empty($_POST['email'])) {
        $error_message = "Please enter your email address.";
    } else {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            // Proceed to change the password
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
                    // Hash the new password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update user password in the database
                    $update_statement = $conn->prepare("UPDATE users SET hash = :hash WHERE email = :email");
                    $update_statement->bindParam(':hash', $hashed_password);
                    $update_statement->bindParam(':email', $_POST['email']);

                    if ($update_statement->execute()) {
                        $success_message = "Your password has been successfully updated. Please log in.";
                        // Redirect to login after a short delay
                        header("refresh:5;url=user_home.php");
                        exit();
                    } else {
                        $error_message = "Error updating password. Please try again.";
                    }
                }
            }
        } else {
            $error_message = "Email address not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../styles/login_style.css">
</head>
<body>
    <div class="login-box">
        <h2>Forgot Password</h2>
        <form action="" method="post">
            <?php if ($error_message): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>
            <label for="email">Enter your email address:</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <button type="submit" name="submit">Change Password</button>
        </form>
        <div class="login-options">
            <a href="login.php">Already have an account? Login here</a>
        </div>
    </div>
</body>
</html>
