<?php
session_start();
require '../includes/header.php';
// Check if user is logged in and is admin
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
    <h1 class="welcome">Welcome to Admin Dashboard</h1>

<div class="dashboard-container">
    <div class="card">
        <img src="../assets/img/waiter.png" alt="Users">
        <h3>Manage Users</h3>
        <p>Control waiter accounts.</p>
    </div>

    <div class="card">
        <img src="../assets/img/menu.png" alt="Menu">
        <h3>Manage Menu</h3>
        <p>Add, edit and remove menu items easily.</p>
    </div>
    <div class="card">
        <img src="../assets/img/order.png" alt="Orders">
        <h3>View Orders</h3>
        <p>Track all customer orders.</p>
    </div>

</div>

<div class="info-section">
    <img src="../assets/img/admin.png" alt="Dashboard Illustration">
    <p>This dashboard gives you a quick overview of your restaurant operations. Use the menu cards above to navigate to different sections.</p>
</div>

    <?php 
        include "../includes/footer.php";
    ?>

</body>
</html>
