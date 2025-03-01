<?php
include("../config/session.php");
include("../config/db.php");
include("../config/auth.php"); // Super Admin Only

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];

    $sql = "INSERT INTO Admin (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        $message = "Admin added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

$admins = $conn->query("SELECT * FROM Admin");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Admins</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include("../includes/header.php"); ?>
    <?php include("../includes/sidebar.php"); ?>

    <div class="content">
        <h1>Manage Admins</h1>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role">
                <option value="admin">Admin</option>
                <option value="superadmin">Super Admin</option>
            </select>
            <button type="submit">Add Admin</button>
        </form>

        <h2>Existing Admins</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
            <?php while ($row = $admins->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row["admin_id"]; ?></td>
                    <td><?php echo $row["username"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                    <td><?php echo ucfirst($row["role"]); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>