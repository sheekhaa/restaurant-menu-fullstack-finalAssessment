<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

if (!isset($_GET['id'], $_GET['status'])) {
    die("Invalid request");
}

$orderId = (int) $_GET['id'];
$status  = $_GET['status'];

// Allow only valid statuses
$allowedStatuses = ['started', 'completed'];
if (!in_array($status, $allowedStatuses)) {
    die("Invalid status");
}

// Update order status
$sql = "UPDATE orders SET status = :status WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':status' => $status,
    ':id' => $orderId
]);

// Flash message
$_SESSION['flash_message'] = "Order status updated successfully";

header("Location: admin_orders.php");
exit;
