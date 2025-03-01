<?php
include "../config/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/department.css">
</head>
<body class="bg-gray-100 p-10">
    <h2 class="page-title">Department Management</h2>

    <h3 class="section-title">Add New Department</h3>
    <form action="../actions/add_department.php" method="POST" class="form-container">
        <label class="label">Department Name:</label>
        <input type="text" name="dept_name" required class="input-field">

        <h4 class="section-title">HOD Details</h4>
        <label class="label">HOD Name:</label>
        <input type="text" name="hod_name" required class="input-field">

        <label class="label">HOD Email:</label>
        <input type="email" name="hod_email" required class="input-field">

        <label class="label">HOD Phone:</label>
        <input type="text" name="hod_phone" required class="input-field">

        <button type="submit" class="btn-submit">Add Department</button>
    </form>

    <h3 class="section-title">Department List</h3>
    <table class="table-container">
        <tr class="table-header">
            <th>Department ID</th>
            <th>Department Name</th>
            <th>HOD Email</th>
            <th>HOD Phone</th>
            <th>Actions</th>
        </tr>
        <?php
        $sql = "SELECT d.dept_id, d.dept_name, h.email, h.phone 
                FROM Department d 
                LEFT JOIN HOD h ON d.hod_id = h.hod_id";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr class='table-row'>
                <td>{$row['dept_id']}</td>
                <td>{$row['dept_name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>
                    <a href='../actions/delete_department.php?id={$row['dept_id']}' class='delete-link'>Delete</a> | 
                    <a href='edit_department.php?id={$row['dept_id']}' class='edit-link'>Edit</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</body>
</html>