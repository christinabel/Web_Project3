<?php
session_start();
include '../config/connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $check = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "An account with this username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $success = "Admin registered successfully! You can now log in.";
            } else {
                $error = "Something went wrong. Please try again.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Registration</title>
  <link rel="stylesheet" href="style.css" /> 
</head>
<body>
  <div class="login-container">
    <form action="admin_register.php" method="POST" class="login-form">
      <h2>Admin Registration</h2>
      <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>
      <div class="input-group">
        <label for="username">Username</label>
        <input type="text" name="username" required />
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" name="password" required />
      </div>
      <div class="input-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" required />
      </div>
      <button type="submit">Register</button>
      <p class="login-link">Already have an account? <a href="admin_login.php">Login</a></p>
    </form>
  </div>
</body>
</html>
