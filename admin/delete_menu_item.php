<?php
require "../config/db.php";
// Ensure menu item ID is provided
if (!isset($_GET['id'])) {
    die("Menu item ID missing");
}

$id = $_GET['id'];
// Delete menu item from database
$sql = "DELETE FROM menu_items WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

header("Location: add_menu.php");
exit;
