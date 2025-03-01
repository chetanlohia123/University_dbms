<?php
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dept_name = $_POST['dept_name'];
    $hod_name = $_POST['hod_name'];
    $hod_email = $_POST['hod_email'];
    $hod_phone = $_POST['hod_phone'];

    // Insert into Department Table
    $sql = "INSERT INTO Department (dept_name) VALUES ('$dept_name')";
    if ($conn->query($sql)) {
        $dept_id = $conn->insert_id; // Get the auto-generated dept_id

        // Insert into HOD Table
        $sql_hod = "INSERT INTO HOD (dept_id, email, phone) VALUES ('$dept_id', '$hod_email', '$hod_phone')";
        if ($conn->query($sql_hod)) {
            $hod_id = $conn->insert_id;

            // Update Department with HOD ID
            $sql_update = "UPDATE Department SET hod_id = '$hod_id' WHERE dept_id = '$dept_id'";
            $conn->query($sql_update);

            header("Location: ../admin/department.php?success=Department added");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: " . $conn->error;
    }
}
?>