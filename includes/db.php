<?php
// Change these as per your environment
$host = 'localhost';
$dbname = 'newsblog';       // Your database name
$username = 'root';         // DB username (default in XAMPP)
$password = '';             // DB password (empty for XAMPP by default)

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: Set charset to UTF-8
$conn->set_charset("utf8");
?>
