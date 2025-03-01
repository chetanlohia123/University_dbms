<?php
session_start();
include "../config/db.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch university stats
$student_count = $conn->query("SELECT COUNT(*) FROM Student")->fetch_row()[0];
$faculty_count = $conn->query("SELECT COUNT(*) FROM Faculty")->fetch_row()[0];
$department_count = $conn->query("SELECT COUNT(*) FROM Department")->fetch_row()[0];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <div class="dashboard-container">
        <h2>Welcome, Admin!</h2>
        <div class="stats">
            <p>📚 Total Departments: <?= $department_count ?></p>
            <p>👩‍🏫 Total Faculty: <?= $faculty_count ?></p>
            <p>🎓 Total Students: <?= $student_count ?></p>
        </div>
        <a href="department.php">Manage Departments</a>
        <a href="course.php">Manage Courses</a>
        <a href="faculty.php">Manage Faculty</a>
        <a href="student.php">Manage Students</a>
    </div>
</body>
</html>