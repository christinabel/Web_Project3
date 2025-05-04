<?php
session_start();
include '../config/connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare insert
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $success = "Registration successful! <a href='login.php'>Login now</a>.";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="login-container">
    <form action="register.php" method="POST" class="login-form">
      <h2>Register</h2>
      <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php elseif ($success): ?>
        <div class="success"><?= $success ?></div>
      <?php endif; ?>
      <div class="input-group">
        <label for="name">Full Name</label>
        <input type="text" name="name" required />
      </div>
      <div class="input-group">
        <label for="email">Email</label>
        <input type="email" name="email" required />
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
      <p class="register-link">Already have an account? <a href="login.php">Login</a></p>
    </form>
  </div>
</body>
</html>
