<?php
session_start();
require '../includes/header.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    die("Access denied");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
     <link rel="stylesheet" type="text/css" href="../assets/admin_css/style.css">
</head>
<body>
    <div style="flex: 1;">
    <?php 
        include "../includes/footer.php";
    ?>
</div>
</body>
</html>
