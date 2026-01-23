<?php
require "../config/db.php";

if (!isset($_GET['id'])) {
    die("Menu item ID missing");
}

$id = $_GET['id'];

$sql = "DELETE FROM menu_items WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

header("Location: add_menu.php");
exit;
