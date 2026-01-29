<?php
require "../config/db.php";
session_start();

// Protect waiter page
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'waiter') {
    die("Access denied");
}

// AJAX request to filter menu items by category
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {

    $params = [];
    $where = "";
    // If a category is selected, add WHERE clause
    if (!empty($_GET['category_id'])) {
        $where = "WHERE menu_items.category_id = :category_id";
        $params[':category_id'] = $_GET['category_id'];
    }
    // Fetch menu items with category names
    $sql = "SELECT menu_items.*, categories.name AS category_name
            FROM menu_items
            JOIN categories ON menu_items.category_id = categories.id
            $where
            ORDER BY menu_items.name ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Output table rows dynamically
    foreach ($menuItems as $item) {
        echo "<tr>
            <td>{$item['id']}</td>
            <td>" . htmlspecialchars($item['category_name']) . "</td>
            <td>" . htmlspecialchars($item['name']) . "</td>
            <td>Rs " . number_format($item['price'], 2) . "</td>
            <td>" . ucfirst($item['availability']) . "</td>
            <td>";

        if ($item['availability'] === 'yes') {
            echo "<a href='add_to_cart.php?id={$item['id']}' class='add-to-cart'>Add to Cart</a>";
        } else {
            echo "Not Available";
        }

        echo "</td></tr>";
    }
    exit;
}
require '../includes/waiter_header.php';


// Capture flash message
$flash = $_SESSION['flash_message'] ?? '';
unset($_SESSION['flash_message']);

// Fetch categories
$sql = "SELECT * FROM categories ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all menu items initially
$sql = "SELECT menu_items.*, categories.name AS category_name
        FROM menu_items
        JOIN categories ON menu_items.category_id = categories.id
        ORDER BY menu_items.name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Menu</title>
    <link rel="stylesheet" type="text/css" href="../assets/waiter_css/style.css">
</head>
<body>

<!-- Category Filter -->
<form style="margin:20px;">
    <label>Filter by Category:</label>
    <select id="categoryFilter" style="padding: 6px; border: 1px solid black; border-radius: 8px; outline: none;">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>">
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
    <tbody id="menuTableBody">
        <?php foreach ($menuItems as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['category_name']) ?></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>Rs <?= number_format($item['price'], 2) ?></td>
            <td><?= ucfirst($item['availability']) ?></td>
            <td>
                <?php if ($item['availability'] == 'yes'): ?>
                    <a href="add_to_cart.php?id=<?= $item['id'] ?>" class="add-to-cart">Add to Cart</a>
                <?php else: ?>Not Available<?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php require '../includes/footer.php'; ?> 
<script src="../assets/js/menu_filter.js"></script>
</body>
</html>
