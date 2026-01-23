<?php
require '../includes/functions.php';
require "../config/db.php";

if (!isset($_GET['id'])) {
    die("Category ID missing");
}

$id = $_GET['id'];

// Delete category
$sql = "DELETE FROM categories WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

header("Location: categories.php");
exit;
