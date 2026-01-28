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
    <h1 class="welcome">Welcome to Waiter Dashboard</h1>

<div class="dashboard-container">
    <div class="card">
        <img src="../assets/img/order-menu.png" alt="Menu">
        <h3>View Menu</h3>
        <p>Browse available food items quickly.</p>
    </div>

    <div class="card">
        <img src="../assets/img/add-to-cart.png" alt="Orders">
        <h3>Take Orders</h3>
        <p>Add items to customer orders easily.</p>
    </div>

    <div class="card">
        <img src="../assets/img/order-status.png" alt="Status">
        <h3>Order Status</h3>
        <p>Check availability and order progress.</p>
    </div>
</div>

<div class="info-section">
    <img src="../assets/img/chef.png" alt="Waiter Illustration">
    <p>This dashboard helps waiters manage customer orders and provide fast service in the restaurant.</p>
</div>

    <?php 
        include "../includes/footer.php";
    ?>
</body>
</html>



