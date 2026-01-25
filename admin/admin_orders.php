<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    die("Access denied");
}

// Fetch all orders with waiter name
$sql = "SELECT orders.*, users.username AS waiter_name 
        FROM orders 
        JOIN users ON orders.waiter_id = users.id
        ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

require '../includes/header.php';
?>

<<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orders</title>
    <link rel="stylesheet" type="text/css" href="../assets/admin_css/style.css">
</head>
<body>
    <div style="flex: 1;">
    <h2 style="text-align:center; margin-top:20px;">All Orders</h2>
<?php if (isset($_SESSION['flash_message'])): ?>
    <p style="color:green; text-align:center; font-weight:bold;">
        <?= $_SESSION['flash_message'] ?>
    </p>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<table class="category-table" style="width:90%; margin:20px auto;">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Waiter</th>
            <th>Items</th>
            <th>Total (Rs)</th>
            <th>Status</th>
            <th>Action</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['waiter_name']) ?></td>
                <td>
                    <?php
                        // Fetch items for this order
                        $sqlItems = "SELECT menu_items.name, order_items.quantity 
                                     FROM order_items 
                                     JOIN menu_items ON order_items.menu_item_id = menu_items.id 
                                     WHERE order_items.order_id = :order_id";
                        $stmtItems = $pdo->prepare($sqlItems);
                        $stmtItems->execute([':order_id' => $order['id']]);
                        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($items as $it) {
                            echo htmlspecialchars($it['name']) . " x " . $it['quantity'] . "<br>";
                        }
                    ?>
                </td>
                <td>Rs <?= number_format($order['total_amount'], 2) ?></td>
                <td><?= ucfirst($order['status']) ?></td>
                <td>
                    <?php if ($order['status'] == 'pending'): ?>
                        <a href="update_order_admin.php?id=<?= $order['id'] ?>&status=started" class="edit-btn">
                            Start
                        </a>
                    <?php elseif ($order['status'] == 'started'): ?>
                        <a href="update_order_admin.php?id=<?= $order['id'] ?>&status=completed" class="edit-btn">
                            Complete
                        </a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?= $order['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- <div style="text-align:center; margin:20px;">
    <a href="admin_dashboard.php">Back to Dashboard</a>
</div> -->
<?php require '../includes/footer.php'; ?>
</div>
</body>
</html>


