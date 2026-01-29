<?php
session_start();
require "../config/db.php";

//only logged-in waiters can access
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'waiter') {
    die("Access denied");
}
// Check if item ID is provided in GET request
if (!isset($_GET['id'])) {
    die("Item ID missing");
}

$item_id = $_GET['id'];

// Initialize cart array in session if not already
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item or increase quantity, if item is already in cart
if (isset($_SESSION['cart'][$item_id])) {
    $_SESSION['cart'][$item_id] += 1;
} else {
    $_SESSION['cart'][$item_id] = 1;
}

// Set flash message
$_SESSION['flash_message'] = "Item added to cart successfully";

// Redirect back to menu
header("Location: menu_list.php");
exit;
