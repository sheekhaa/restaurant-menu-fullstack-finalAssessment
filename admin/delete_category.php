<?php
session_start();
require "../config/db.php";
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    die("Access denied");
}

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
