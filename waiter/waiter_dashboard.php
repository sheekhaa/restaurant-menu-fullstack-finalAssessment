<?php
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
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>

    <header class="header-content">
        <p class="logo">Waiter Dashboard</p>
        <nav class="nav-content">
            <a href="menu_list.php">Menu</a>
            <a href="cart.php">Cart</a>
            <a href="orders.php">Orders</a>
            <a href="../admin/logout.php" class="logout">Logout</a>
        </nav>
    </header>

<div class="dashboard-content">
    <h3>Welcome, Waiter</h3>
    <p>Select menu items and take customer orders.</p>
</div>
    <?php 
        include "../includes/footer.php";
    ?>
</body>
</html>
