<?php
session_start();
include('../includes/db.php');

if (isset($_POST['submit'])) {
    $error = array();

    if (empty($_POST['email'])) {
        $error['email'] = "Input Email";
    }

    if (empty($_POST['hash'])) {
        $error['hash'] = "Input Password";
    }

    if (empty($error)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=:em");
        $stmt->bindParam(":em", $_POST['email']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_BOTH);

        if ($stmt->rowCount() > 0 && password_verify($_POST['hash'], $row['hash'])) {
            $_SESSION['user_id'] = $row['user_id'];
            header("location: user_home.php");
            exit();
        } elseif ($stmt->rowCount() == 0) {
            // Display an error message for the email field
            $error_message = "Email is incorrect";
        } else {
            // Display a general error message for the password field
            $error_message = "Password is incorrect";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capital International Bank</title>
    <link rel="stylesheet" href="../styles/login_style.css">
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="../img/Logo.svg" alt="Capital International Group Logo">
            </div>
            <ul class="navbar-nav">
                <li><a href="#">Services</a></li>
                <li><a href="#">Solutions</a></li>
                <li><a href="#">Resources</a></li>
                <li><a href="#">Company</a></li>
            </ul>
            <div class="nav-buttons">
                <div class="dropdown">
                    <button class="login-btn">Login</button>
                </div>
                <div class="dropdown">
                    <button class="get-started-btn">Get started</button>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <section class="login-container">
        <div class="login-box">
            <h1>Welcome to <br> Capital International Bank.</h1>
            <form action="user_home.php" method="post">
                <?php
                  if (isset($error_message)) {
                      echo '<p id="disappearing-text" style="color: red;">' . htmlspecialchars($error_message) . '</p>';
                  }
                  ?>
                  <script>
                    // JavaScript code to make the text disappear after 5 seconds
                    setTimeout(function() {
                        var disappearingText = document.getElementById('disappearing-text');
                        disappearingText.style.display = 'none';
                    }, 3000); 
                  </script>
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Email address" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="hash" placeholder="Password" required>
                
                <div class="login-options">
                    <a href="forgot.php">Forgot your password?</a>
                    <a href="signup.php">Don't have an account? Contact us</a>
                </div>
                
                <button name="submit" type="submit" class="login-btn">Login to account</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>
            Regulated activities are carried out on behalf of the Capital International Group by its licensed member companies. Full terms of business, policies, and company details can be viewed <a href="#">here</a>.
        </p>
    </footer>
</body>
</html>
