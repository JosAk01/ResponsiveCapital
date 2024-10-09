<?php
include('../includes/db.php');

if (isset($_POST['submit'])) {
  $error = array(); // Initialize an array to store errors

  // Check if the 'name' field is empty
  if (empty($_POST['name'])) {
    $error['name'] = "Name is required";
  }

  // Check if the 'email' field is empty
  if (empty($_POST['email'])) {
    $error['email'] = "Email is required";
  }else{
    $statement = $conn->prepare("SELECT * FROM users WHERE email = :em");
    $statement->bindParam(":em",$_POST['email']);
    $statement->execute();
    if($statement->rowCount() > 0){
      $error['email'] = "Email already Exists";
    }
  }

  // Check if the 'hash' (password) field is empty
  if (empty($_POST['hash'])) {
    $error['password'] = "Password is required";
  }
  
  // Check if 'hash' and 'confirm_hash' match
  if (empty($_POST['confirm_hash'])) {
    $error['confirm_hash'] = "Confirm Password is required";
  } elseif ($_POST['hash'] !== $_POST['confirm_hash']) {
    $error['confirm_hash'] = "Password Mismatch";
  }

  // If there are no errors, you can proceed with processing the form data
  if (empty($error)) {
    $encrypted = password_hash($_POST['hash'], PASSWORD_BCRYPT);
$stmt = $conn->prepare("INSERT INTO users VALUES (NULL, :nm, :em, :hsh, NOW())");
// Process the form data here, e.g., store it in a database or perform other actions
$data = array(
    ":nm" => $_POST['name'],
    ":em" => $_POST['email'],
    ":hsh" => $encrypted
);
$stmt->execute($data);
    // After successful processing, you can redirect the user to another page
    header("Location:login.php");
    exit(); // Make sure to exit after redirection to prevent further code execution
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
            <form action="" method="post">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Name" required>

                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Email address" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="hash" placeholder="Password" required>
                    <br>
                  <?php if(isset($error['confirm_hash'])) { ?>
                    <p style="color:red"><?php echo $error['confirm_hash']; ?></p>
                  <?php } ?>
                <label for="password">Confirm Password</label>
                <input type="password" id="password" name="confirm_hash" placeholder="Comfirm Password" required>
                <div class="signup-options">
                    <p href="#">Already a user? <a href="login.php">Login</a></p>
                </div>
                
                <button name="submit" type="submit" class="login-btn">Signup to account</button>
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
