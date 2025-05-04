<?php
session_start();
include '../config/connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($admin_id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $admin_id;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No admin account found with that username.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login</title>
  <link rel="stylesheet" href="style.css" /> 
</head>
<body>
  <div class="login-container">
    <form action="admin_login.php" method="POST" class="login-form">
      <h2>Admin Login</h2>
      <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <div class="input-group">
        <label for="username">Username</label>
        <input type="text" name="username" required />
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" name="password" required />
      </div>
      <button type="submit">Login</button>
      <p class="register-link">Don't have an account? <a href="admin_register.php">Register here</a>.</p>
      <p class="back-link"><a href="../homepage.html">← Back to Homepage</a></p>
    </form>
  </div>
</body>
</html>
