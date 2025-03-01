<?php
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    $new_password = password_hash("default123", PASSWORD_DEFAULT);
    $query = "UPDATE Admin SET password='$new_password' WHERE email='$email'";

    if ($conn->query($query)) {
        echo "Password reset to default123";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>