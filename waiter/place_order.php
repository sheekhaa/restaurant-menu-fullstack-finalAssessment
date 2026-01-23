<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'waiter') {
    die("Access denied");
}

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die("Cart is empty");
}

// Calculate total
$total = 0;
$ids = implode(',', array_keys($cart));
$sql = "SELECT * FROM menu_items WHERE id IN ($ids)";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($items as $item) {
    $total += $item['price'] * $cart[$item['id']];
}

// Insert into orders table
$sql = "INSERT INTO orders (waiter_id, total_amount) VALUES (:waiter_id, :total)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':waiter_id' => $_SESSION['user_id'],
    ':total' => $total
]);

$order_id = $pdo->lastInsertId();

// Insert each item
$sql = "INSERT INTO order_items (order_id, menu_item_id, quantity, price)
        VALUES (:order_id, :menu_item_id, :quantity, :price)";
$stmt = $pdo->prepare($sql);

foreach ($items as $item) {
    $stmt->execute([
        ':order_id' => $order_id,
        ':menu_item_id' => $item['id'],
        ':quantity' => $cart[$item['id']],
        ':price' => $item['price']
    ]);
}

// Clear cart
unset($_SESSION['cart']);
$_SESSION['flash_message'] = "Order placed successfully";

header("Location: cart.php");
exit;
