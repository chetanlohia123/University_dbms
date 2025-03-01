<?php
include "../config/db.php";

if (isset($_GET['id'])) {
    $dept_id = $_GET['id'];

    // Delete the HOD first (due to foreign key constraint)
    $sql_hod = "DELETE FROM HOD WHERE dept_id = '$dept_id'";
    $conn->query($sql_hod);

    // Delete the Department
    $sql_dept = "DELETE FROM Department WHERE dept_id = '$dept_id'";
    if ($conn->query($sql_dept)) {
        header("Location: ../admin/department.php?success=Department deleted");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>