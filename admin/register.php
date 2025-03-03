<?php
include "../config/db.php";
session_start(); // Start session to store OTP and user data temporarily

$message = "";
$otp_message = "";

// Function to generate a 6-digit OTP
function generateOTP() {
    return rand(100000, 999999);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["register"])) {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        // Check if passwords match
        if ($password !== $confirm_password) {
            $message = "Passwords do not match!";
        } else {
            // Check if email already exists
            $checkQuery = "SELECT * FROM Admin WHERE email = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $message = "Email already registered. <a href='login.php'>Login here</a>";
            } else {
                // Generate OTP
                $otp = generateOTP();
                $_SESSION['otp'] = $otp;
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;

                // Send OTP to email (using PHP's mail function as an example)
                $subject = "Your OTP for Registration";
                $body = "Your OTP is: $otp. Please enter this to verify your email.";
                $headers = "From: no-reply@yourdomain.com";

                if (mail($email, $subject, $body, $headers)) {
                    $otp_message = "OTP sent to your email. Please verify.";
                } else {
                    $message = "Failed to send OTP. Please try again.";
                }
            }
        }
    } elseif (isset($_POST["verify_otp"])) {
        $user_otp = $_POST["otp"];

        if ($user_otp == $_SESSION['otp']) {
            // Insert new admin after OTP verification
            $insertQuery = "INSERT INTO Admin (username, email, passwor) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sss", $_SESSION['name'], $_SESSION['email'], $_SESSION['password']);

            if ($stmt->execute()) {
                $message = "Registration successful! Redirecting to dashboard...";
                header("Refresh: 2; url=dashboard.php"); // Redirect after 2 seconds
                unset($_SESSION['otp']); // Clear OTP after use
                unset($_SESSION['name']);
                unset($_SESSION['email']);
                unset($_SESSION['password']);
            } else {
                $message = "Error: " . $conn->error;
            }
        } else {
            $otp_message = "Invalid OTP. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/login.css">
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
    </style>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="login-box">
        <h2 class="title">Admin Registration</h2>
        <?php if ($message) echo "<p class='success'>$message</p>"; ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required class="input-field">
            <input type="email" name="email" placeholder="Email" required class="input-field">
            <input type="password" name="password" placeholder="Password" required class="input-field">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required class="input-field">
            <button type="submit" name="register" class="btn-submit">Register</button>
        </form>
        <p class="register-link">Already have an account? <a href="login.php">Login</a></p>
    </div>

    <!-- OTP Popup -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="otpPopup">
        <h3>Verify OTP</h3>
        <?php if ($otp_message) echo "<p>$otp_message</p>"; ?>
        <form method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" required class="input-field">
            <button type="submit" name="verify_otp" class="btn-submit">Verify</button>
        </form>
    </div>

    <script>
        <?php if ($otp_message) { ?>
            document.getElementById('otpPopup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        <?php } ?>

        document.getElementById('overlay').onclick = function() {
            document.getElementById('otpPopup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        };
    </script>
</body>
</html>