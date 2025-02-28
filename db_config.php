<?php
$servername = "localhost";
$username = "root";  // Change if needed
$password = "newpassword";  // Change if needed
$dbname = "srmist_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>