<?php
require '../includes/waiter_header.php';
require "../config/db.php";
session_start();
// Capture flash message
$flash = $_SESSION['flash_message'] ?? '';
unset($_SESSION['flash_message']); // remove after showing


// Protect waiter page
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'waiter') {
    die("Access denied");
}

// Fetch categories
$sql = "SELECT * FROM categories ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filter by category if selected
$categoryFilter = "";
$params = [];

if (isset($_GET['category_id']) && $_GET['category_id'] != "") {
    $categoryFilter = "WHERE menu_items.category_id = :category_id";
    $params[':category_id'] = $_GET['category_id'];
}

// Fetch menu items
$sql = "SELECT menu_items.*, categories.name AS category_name
        FROM menu_items
        JOIN categories ON menu_items.category_id = categories.id
        $categoryFilter
        ORDER BY menu_items.name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Menu</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>

<!-- Category Filter -->
<form method="get" style="margin:20px;">
    <label>Filter by Category:</label>
    <select name="category_id" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"
                <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<!-- Menu Table -->
<div class="category-container">
<table class="category-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Item</th>
            <th>Price</th>
            <th>Available</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($menuItems as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['category_name']) ?></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>Rs <?= number_format($item['price'], 2) ?></td>
            <td><?= ucfirst($item['availability']) ?></td>
            <td>
            <?php if ($item['availability'] == 'yes'): ?>
                <a href="add_to_cart.php?id=<?= $item['id'] ?>" class = "add-to-cart">Add to Cart</a>
                <?php else: ?>Not Available<?php endif; ?>
            </td>

        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

</body>
</html>
