<?php
session_start();
include "config/db.php";
require 'vendor/autoload.php'; // For PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$notification = "";

// Function to generate OTP
function generateOTP() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

// Function to send OTP email
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'unimanager.server@gmail.com';
        $mail->Password = 'judflbtfroqxsepl';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('unimanager.server@gmail.com', 'Uni-Manage');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your Uni-Manage OTP';
        $mail->Body = "Your OTP is: <b>$otp</b>. It expires in 5 minutes.";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Handle Login OTP Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["request_login_otp"])) {
    $email = $_POST["email"];
    $stmt = $conn->prepare("SELECT id, username FROM Admin WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $otp = generateOTP();
        $stmt = $conn->prepare("INSERT INTO otp_verification (email, otp) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $otp);
        if ($stmt->execute() && sendOTP($email, $otp)) {
            $_SESSION['pending_login_email'] = $email;
            $notification = "✅ OTP sent to your email!";
        } else {
            $notification = "❌ Failed to send OTP!";
        }
    } else {
        $notification = "❌ Email not registered!";
    }
}

// Handle Login OTP Verification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verify_login_otp"])) {
    $email = $_SESSION['pending_login_email'];
    $otp = $_POST["otp"];
    $stmt = $conn->prepare("SELECT * FROM otp_verification WHERE email=? AND otp=? AND created_at > NOW() - INTERVAL 5 MINUTE");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $stmt = $conn->prepare("SELECT id, username FROM Admin WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();
        $_SESSION['admin'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        unset($_SESSION['pending_login_email']);
        $stmt = $conn->prepare("DELETE FROM otp_verification WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        session_write_close();
        header("Location: dashboard.php");
        exit();
    } else {
        $notification = "❌ Invalid or expired OTP! Please try again.";
        unset($_SESSION['pending_login_email']); // Reset on invalid OTP
        $stmt = $conn->prepare("DELETE FROM otp_verification WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute(); // Clean up invalid OTP
    }
}

// Handle Registration OTP Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["request_register_otp"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $checkQuery = "SELECT * FROM Admin WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $checkResult = $stmt->get_result();

    if ($checkResult->num_rows > 0) {
        $notification = "⚠️ Email already registered!";
    } else {
        $otp = generateOTP();
        $stmt = $conn->prepare("INSERT INTO otp_verification (email, otp) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $otp);
        if ($stmt->execute() && sendOTP($email, $otp)) {
            $_SESSION['pending_register_email'] = $email;
            $_SESSION['pending_register_name'] = $name;
            $notification = "✅ OTP sent to your email for registration!";
        } else {
            $notification = "❌ Failed to send OTP!";
        }
    }
}

// Handle Registration OTP Verification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verify_register_otp"])) {
    $email = $_SESSION['pending_register_email'];
    $name = $_SESSION['pending_register_name'];
    $otp = $_POST["otp"];
    $stmt = $conn->prepare("SELECT * FROM otp_verification WHERE email=? AND otp=? AND created_at > NOW() - INTERVAL 5 MINUTE");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $insertQuery = "INSERT INTO Admin (username, email) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ss", $name, $email);
        if ($stmt->execute()) {
            $stmt = $conn->prepare("DELETE FROM otp_verification WHERE email=?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            unset($_SESSION['pending_register_email']);
            unset($_SESSION['pending_register_name']);
            $notification = "✅ Registration successful! Please login.";
        } else {
            $notification = "❌ Error: " . $conn->error;
        }
    } else {
        $notification = "❌ Invalid or expired OTP! Please try again.";
        unset($_SESSION['pending_register_email']); // Reset on invalid OTP
        unset($_SESSION['pending_register_name']);  // Reset on invalid OTP
        $stmt = $conn->prepare("DELETE FROM otp_verification WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute(); // Clean up invalid OTP
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Uni-Manage - University Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gradient-to-br from-gray-900 to-gray-800 text-white min-h-screen flex flex-col">
    <!-- Navigation Bar -->
    <nav class="bg-gray-800/90 backdrop-blur-sm p-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
        <a href="index.php" class="text-2xl font-bold text-blue-400 hover:text-blue-300 transition-colors">Uni-Manage</a>
    </nav>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col justify-center items-center px-4">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4 text-center bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-green-400">
            Welcome to Uni-Manage
        </h1>
        <p class="text-xl md:text-2xl mb-8 text-gray-300 text-center max-w-2xl">
            Streamline your university management with our intuitive platform
        </p>
        <?php if(!isset($_SESSION['admin'])): ?>
            <div class="space-x-4 flex flex-col sm:flex-row gap-4">
                <button onclick="openPopup('login-popup')" class="btn btn-blue">Login</button>
                <button onclick="openPopup('register-popup')" class="btn btn-green">Register</button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-40 transition-opacity duration-300"></div>

    <!-- Login Popup -->
    <div id="login-popup" class="popup hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
        <div class="bg-gray-800/95 p-6 rounded-xl shadow-2xl w-full max-w-md border border-gray-700">
            <span class="close text-3xl cursor-pointer float-right text-gray-400 hover:text-white transition-colors" onclick="closePopup('login-popup')">×</span>
            <h2 class="text-2xl font-bold mb-6 text-blue-400">🔐 Login with OTP</h2>
            <?php if(!isset($_SESSION['pending_login_email'])): ?>
                <form method="POST">
                    <input type="hidden" name="request_login_otp" value="1">
                    <input type="email" name="email" placeholder="Enter your email" required class="w-full p-3 mb-4 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white placeholder-gray-400">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-semibold transition-colors">Send OTP</button>
                </form>
                <p class="mt-4 text-gray-300 text-center">Don't have an account? <a href="#" onclick="closePopup('login-popup'); openPopup('register-popup')" class="text-blue-400 hover:underline">Register</a></p>
            <?php else: ?>
                <form method="POST">
                    <input type="hidden" name="verify_login_otp" value="1">
                    <input type="text" name="otp" placeholder="Enter OTP" required class="w-full p-3 mb-4 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white placeholder-gray-400" maxlength="6">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-semibold transition-colors">Verify OTP</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Register Popup -->
    <div id="register-popup" class="popup hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
        <div class="bg-gray-800/95 p-6 rounded-xl shadow-2xl w-full max-w-md border border-gray-700">
            <span class="close text-3xl cursor-pointer float-right text-gray-400 hover:text-white transition-colors" onclick="closePopup('register-popup')">×</span>
            <h2 class="text-2xl font-bold mb-6 text-green-400">📝 Admin Registration</h2>
            <?php if(!isset($_SESSION['pending_register_email'])): ?>
                <form method="POST">
                    <input type="hidden" name="request_register_otp" value="1">
                    <input type="text" name="name" placeholder="Full Name" required class="w-full p-3 mb-4 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white placeholder-gray-400">
                    <input type="email" name="email" placeholder="Email" required class="w-full p-3 mb-4 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white placeholder-gray-400">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg font-semibold transition-colors">Send OTP</button>
                </form>
                <p class="mt-4 text-gray-300 text-center">Already have an account? <a href="#" onclick="closePopup('register-popup'); openPopup('login-popup')" class="text-blue-400 hover:underline">Login</a></p>
            <?php else: ?>
                <form method="POST">
                    <input type="hidden" name="verify_register_otp" value="1">
                    <input type="text" name="otp" placeholder="Enter OTP" required class="w-full p-3 mb-4 bg-gray-700/50 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white placeholder-gray-400" maxlength="6">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg font-semibold transition-colors">Verify OTP</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Notification Popup -->
    <div id="notification-popup" class="fixed bottom-6 right-6 bg-gray-800/90 p-4 rounded-lg shadow-xl border border-gray-700 hidden z-50">
        <p id="notification-message" class="text-gray-200"><?= $notification ?></p>
    </div>

    <script>
        function openPopup(id) {
            document.getElementById(id).classList.remove("hidden");
            document.getElementById("overlay").classList.remove("hidden");
        }

        function closePopup(id) {
            document.getElementById(id).classList.add("hidden");
            document.getElementById("overlay").classList.add("hidden");
        }

        function showNotification(message) {
            if (!message.trim()) return;
            let popup = document.getElementById("notification-popup");
            let msg = document.getElementById("notification-message");
            msg.innerHTML = message;
            popup.classList.remove("hidden");
            setTimeout(() => popup.classList.add("hidden"), 3000);
        }

        let notificationMessage = "<?= $notification ?>";
        if (notificationMessage.trim() !== "") {
            showNotification(notificationMessage);
        }
    </script>
</body>
</html>