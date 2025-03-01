<?php
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dept_name = $_POST['dept_name'];
    $hod_name = $_POST['hod_name'];
    $hod_email = $_POST['hod_email'];
    $hod_phone = $_POST['hod_phone'];

    // Insert HOD and get ID
    $query_hod = "INSERT INTO HOD (hod_name, email, phone) VALUES ('$hod_name', '$hod_email', '$hod_phone')";
    if ($conn->query($query_hod)) {
        $hod_id = $conn->insert_id;

        // Insert department with HOD ID
        $query_dept = "INSERT INTO Department (dept_name, hod_id) VALUES ('$dept_name', '$hod_id')";
        if ($conn->query($query_dept)) {
            header("Location: ../admin/department.php");
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: " . $conn->error;
    }
}
?>