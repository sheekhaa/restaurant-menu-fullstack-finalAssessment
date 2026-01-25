<?php
require '../includes/waiter_header.php';
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'waiter') {
    die("Access denied");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Waiter Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/waiter_css/style.css">
</head>
<body>

    <?php 
        include "../includes/footer.php";
    ?>
</body>
</html>



