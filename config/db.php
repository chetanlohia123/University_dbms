<?php
$host = "localhost"; // Change if using a remote DB
$user = "root"; // Your MySQL username
$pass = "newpassword"; // Your MySQL password
$dbname = "srmist_db"; // Your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>