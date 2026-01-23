<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'waiter') {
    die("Access denied");
}

// Fetch all orders for this waiter
$sql = "SELECT * FROM orders WHERE waiter_id = :waiter_id ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':waiter_id' => $_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

require '../includes/header.php';
?>

<h2 style="text-align:center; margin-top:20px;">Your Orders</h2>

<?php if (isset($_SESSION['flash_message'])): ?>
    <p style="color:green; text-align:center; font-weight:bold;">
        <?= $_SESSION['flash_message'] ?>
    </p>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<table class="category-table" style="width:80%; margin:20px auto;">
    <thead>
        <tr>
            <th>Order ID</th>
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
                        <a href="update_order.php?id=<?= $order['id'] ?>&status=started" class="edit-btn">
                            Start
                        </a>
                    <?php elseif ($order['status'] == 'started'): ?>
                        <a href="update_order.php?id=<?= $order['id'] ?>&status=completed" class="edit-btn">
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

<div style="text-align:center; margin:20px;">
    <a href="menu_list.php">Back to Menu</a>
</div>

<?php require '../includes/footer.php'; ?>
