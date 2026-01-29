<?php
require "../config/db.php";
// Get the category filter from GET request(default to empty string)
$categoryId = $_GET['category_id'] ?? '';
// Base SQL query: fetch all menu items along with their category names
$sql = "SELECT menu_items.*, categories.name AS category_name
        FROM menu_items
        JOIN categories ON menu_items.category_id = categories.id";

$params = [];//parameters array for prepared statement

// If a category is selected, filter by category
if ($categoryId !== "") {
    $sql .= " WHERE menu_items.category_id = :category_id";
    $params[':category_id'] = $categoryId;
}
// Sort menu items alphabetically by name
$sql .= " ORDER BY menu_items.name ASC";
// Prepare and execute SQL statement
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
// Fetch all results and output as JSON (for AJAX)
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
