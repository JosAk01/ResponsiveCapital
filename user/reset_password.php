<?php
include('../includes/db.php');

if (isset($_POST['submit'])) {
    $error = array();

    if (empty($_POST['new_password'])) {
        $error['new_password'] = "Enter a new password.";
    }

    if (empty($error)) {
        // Validate the reset token from the URL
        $reset_token = $_GET['token'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=:token AND reset_expiry > NOW()");
        $stmt->bindParam(":token", $reset_token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Hash the new password and update it in the database
            $hashed_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET hash=:password, reset_token=NULL, reset_expiry=NULL WHERE email=:em");
            $update_stmt->bindParam(":password", $hashed_password);
            $update_stmt->bindParam(":em", $user['email']);
            $update_stmt->execute();

            // Redirect to the login page or display success message
            header("Location: login.php");
            exit();
        } else {
            $error['reset_token'] = "Invalid or expired reset token.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../styles/login_style.css">
</head>
<body>
    <div class="login-box">
        <h2>Reset Password</h2>
        <form action="" method="post">
            <?php if (isset($error['reset_token'])): ?>
                <p style="color: red;"><?php echo $error['reset_token']; ?></p>
            <?php endif; ?>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
            
            <button type="submit" name="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
