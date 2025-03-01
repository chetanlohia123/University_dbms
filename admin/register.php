<?php
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $query = "INSERT INTO Admin (email, password, verified) VALUES ('$email', '$password', 0)";
    if ($conn->query($query)) {
        echo "Verification email sent!";
        header("Location: verify_email.php?email=$email");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Register</button>
</form>