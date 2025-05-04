<?php
session_start();
require '../config/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle update user
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $email, $id);
    $stmt->execute();
}

// Handle delete user
if (isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];
    $conn->query("DELETE FROM users WHERE id=$id");
}

// Fetch users
$users = $conn->query("SELECT * FROM users");

// Fetch game sessions
$filter = "";
if (!empty($_GET['user_filter'])) {
    $uid = (int)$_GET['user_filter'];
    $filter = "WHERE user_id = $uid";
}
$sessions = $conn->query("SELECT * FROM game_sessions $filter ORDER BY start_time DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="form-container">
    <h2>Admin Dashboard</h2>
    <p><a href="logout.php">Logout</a></p>

    <h3>👤 Manage Users</h3>
    <table border="1" cellpadding="8">
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Edit</th><th>Delete</th></tr>
        <?php while ($row = $users->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                    <td><?= $row['id'] ?></td>
                    <td><input type="text" name="name" value="<?= $row['name'] ?>"></td>
                    <td><input type="email" name="email" value="<?= $row['email'] ?>"></td>
                    <td><button type="submit" name="update_user">Update</button></td>
                    <td><a href="?delete_user=<?= $row['id'] ?>" onclick="return confirm('Delete this user?')">❌</a></td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>🎮 Game Sessions</h3>
    <form method="GET">
        <input type="number" name="user_filter" placeholder="Filter by User ID">
        <button type="submit">Filter</button>
    </form>
    <table border="1" cellpadding="8">
        <tr>
            <th>Session_ID</th>
            <th>User ID</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Generations</th>
        </tr>
        <?php while ($row = $sessions->fetch_assoc()): ?>
            <tr>
                <td><?= $row['session_id'] ?></td> 
                <td><?= $row['user_id'] ?></td>
                <td><?= $row['start_time'] ?></td>
                <td><?= $row['end_time'] ?? '—' ?></td>
                <td><?= $row['generations'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
