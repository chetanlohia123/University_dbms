<?php
// Only Super Admins can access certain pages
if ($_SESSION['role'] !== 'superadmin') {
    header("Location: dashboard.php");
    exit();
}
?>