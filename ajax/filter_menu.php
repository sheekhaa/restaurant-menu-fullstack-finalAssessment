<?php
require "../config/db.php";

$categoryId = $_GET['category_id'] ?? '';

$sql = "SELECT menu_items.*, categories.name AS category_name
        FROM menu_items
        JOIN categories ON menu_items.category_id = categories.id";

$params = [];

if ($categoryId !== "") {
    $sql .= " WHERE menu_items.category_id = :category_id";
    $params[':category_id'] = $categoryId;
}

$sql .= " ORDER BY menu_items.name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
