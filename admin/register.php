<?php
include "../config/db.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $query = "INSERT INTO Admin (name, email, password) VALUES ('$name', '$email', '$password')";
    if ($conn->query($query)) {
        $message = "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="login-box">
        <h2 class="title">Admin Registration</h2>
        <?php if ($message) echo "<p class='success'>$message</p>"; ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required class="input-field">
            <input type="email" name="email" placeholder="Email" required class="input-field">
            <input type="password" name="password" placeholder="Password" required class="input-field">
            <button type="submit" class="btn-submit">Register</button>
        </form>
    </div>
</body>
</html>