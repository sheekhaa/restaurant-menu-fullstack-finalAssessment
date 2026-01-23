<?php
require '../includes/waiter_header.php';
session_start();
require "../config/db.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'waiter') {
    die("Access denied");
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
$items = [];

if (!empty($cart)) {
    $ids = implode(",", array_keys($cart));
    $sql = "SELECT * FROM menu_items WHERE id IN ($ids)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="category-container">
<table class="category-table">
    <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>

<?php foreach ($items as $item): 
    $qty = $cart[$item['id']];
    $subtotal = $item['price'] * $qty;
    $total += $subtotal;
?>
<tr>
    <td><?= htmlspecialchars($item['name']) ?></td>
    <td>Rs <?= number_format($item['price'],2) ?></td>
    <td><?= $qty ?></td>
    <td>Rs <?= number_format($subtotal,2) ?></td>
</tr>
<?php endforeach; ?>

<tr>
    <th colspan="3">Total</th>
    <th>Rs <?= number_format($total,2) ?></th>
</tr>
    </tbody>
</table>

<div style="text-align:center; margin-top:20px;">
    <?php if (!empty($items)): ?>
        <form method="POST" action="place_order.php">
            <button type="submit" class="btn">Place Order</button>
        </form>
    <?php endif; ?>
</div>

</div>

</body>
</html>
