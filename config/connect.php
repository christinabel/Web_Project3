<?php
$host = "localhost";
$user = "cbelette1";
$pass = "cbelette1";
$dbname = "cbelette1";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully!";
?>
