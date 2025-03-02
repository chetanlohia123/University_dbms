<?php
session_start();
include "../config/db.php";

// Restrict access to admin only
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetching counts for dashboard statistics
$department_count = $conn->query("SELECT COUNT(*) FROM Department")->fetch_row()[0];
$faculty_count = $conn->query("SELECT COUNT(*) FROM Faculty")->fetch_row()[0];
$student_count = $conn->query("SELECT COUNT(*) FROM Student")->fetch_row()[0];
$course_count = $conn->query("SELECT COUNT(*) FROM Course")->fetch_row()[0];

// Fetching recent activity
$recent_students = $conn->query("SELECT student_name, email FROM Student ORDER BY student_id DESC LIMIT 5");
$recent_faculty = $conn->query("SELECT faculty_name, email FROM Faculty ORDER BY faculty_id DESC LIMIT 5");
$recent_courses = $conn->query("SELECT course_name FROM Course ORDER BY course_id DESC LIMIT 5");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body class="bg-gray-100">
    <div class="dashboard-container">
        <h1 class="title">Admin Dashboard</h1>
        
        <!-- Dashboard Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>📚 Total Departments</h3>
                <p><?= $department_count ?></p>
            </div>
            <div class="stat-card">
                <h3>👩‍🏫 Total Faculty</h3>
                <p><?= $faculty_count ?></p>
            </div>
            <div class="stat-card">
                <h3>🎓 Total Students</h3>
                <p><?= $student_count ?></p>
            </div>
            <div class="stat-card">
                <h3>📖 Total Courses</h3>
                <p><?= $course_count ?></p>
            </div>
            
        </div>

        <!-- Management Options -->
        <div class="manage-grid">
            <a href="manage_departments.php" class="manage-card">🏛 Manage Departments</a>
            <a href="manage_students.php" class="manage-card">🎓 Manage Students</a>
            <a href="manage_faculty.php" class="manage-card">👨‍🏫 Manage Faculty</a>
            <a href="manage_courses.php" class="manage-card">📖 Manage Courses</a>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h2>🔄 Recent Activity</h2>
            <div class="recent-section">
                <h3>🎓 Recently Added Students</h3>
                <ul>
                    <?php while ($student = $recent_students->fetch_assoc()) {
                        echo "<li>{$student['name']} - {$student['email']}</li>";
                    } ?>
                </ul>
            </div>
            <div class="recent-section">
                <h3>👨‍🏫 Recently Added Faculty</h3>
                <ul>
                    <?php while ($faculty = $recent_faculty->fetch_assoc()) {
                        echo "<li>{$faculty['name']} - {$faculty['email']}</li>";
                    } ?>
                </ul>
            </div>
            <div class="recent-section">
                <h3>📖 Recently Added Courses</h3>
                <ul>
                    <?php while ($course = $recent_courses->fetch_assoc()) {
                        echo "<li>{$course['course_name']}</li>";
                    } ?>
                </ul>
            </div>
        </div>

        <!-- Logout Button -->
        <a href="../actions/logout.php" class="logout-btn">🚪 Logout</a>
    </div>
</body>
</html>