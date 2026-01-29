<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'waiter') {
    die("Access denied");
}
// Validate GET parameters
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    die("Invalid request");
}

$order_id = $_GET['id'];
$status = $_GET['status'];

// Make sure this order belongs to the logged-in waiter
$sqlCheck = "SELECT * FROM orders WHERE id = :id AND waiter_id = :waiter_id";
$stmtCheck = $pdo->prepare($sqlCheck);
$stmtCheck->execute([':id' => $order_id, ':waiter_id' => $_SESSION['user_id']]);
$order = $stmtCheck->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found or access denied");
}

// Update order status
$sqlUpdate = "UPDATE orders SET status = :status WHERE id = :id";
$stmtUpdate = $pdo->prepare($sqlUpdate);
$stmtUpdate->execute([':status' => $status, ':id' => $order_id]);

$_SESSION['flash_message'] = "Order $order_id status updated to $status ";
header("Location: orders.php");
exit;
