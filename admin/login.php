<?php
session_start();
include "../config/db.php";

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

// Handle login
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Fetch admin details
    $query = "SELECT id, username, password FROM Admin WHERE email='$email'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        // Compare plain text passwords (NO HASHING)
        if ($password === $admin["password"]) {
            $_SESSION['admin'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="login-box">
        <h2 class="title">Admin Login</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required class="input-field">
            <input type="password" name="password" placeholder="Password" required class="input-field">
            <button type="submit" class="btn-submit">Login</button>
        </form>
        <p class="register-link">Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>