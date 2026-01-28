<?php
session_start();
require "../includes/functions.php";
require "../config/db.php";
$message = "";
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    die("Access denied");
}

// AJAX search handler
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $params = [];
    $where = [];

    // Search by item name
    if (!empty($_GET['search'])) {
        $where[] = "menu_items.name LIKE :search";
        $params[':search'] = "%" . $_GET['search'] . "%";
    }

    // Filter by category if sent
    if (!empty($_GET['category_id'])) {
        $where[] = "menu_items.category_id = :category_id";
        $params[':category_id'] = $_GET['category_id'];
    }

    $whereSQL = "";
    if (!empty($where)) {
        $whereSQL = "WHERE " . implode(" AND ", $where);
    }

    $sql = "SELECT menu_items.*, categories.name AS category_name
            FROM menu_items
            JOIN categories ON menu_items.category_id = categories.id
            $whereSQL
            ORDER BY menu_items.id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return only table rows
    foreach ($menuItems as $item) {
        echo "<tr>
            <td>{$item['id']}</td>
            <td>" . htmlspecialchars($item['category_name']) . "</td>
            <td>" . htmlspecialchars($item['name']) . "</td>
            <td>Rs " . number_format($item['price'], 2) . "</td>
            <td>" . ucfirst($item['availability']) . "</td>
            <td class='action-icons'>
                <a href='edit_menu_item.php?id={$item['id']}' class='edit-btn'>Edit</a>
                <a href='delete_menu_item.php?id={$item['id']}' class='delete-btn' onclick='return confirm(\"Delete this item?\");'>Delete</a>
            </td>
        </tr>";
    }
    exit; // Stop full page from rendering
}

// Fetch categories 
$sql = "SELECT * FROM categories ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Add menu item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token");
    }
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    $sql = "INSERT INTO menu_items (category_id, name, price, availability)
            VALUES (:category_id, :name, :price, :availability)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':category_id' => $category_id,
        ':name' => $name,
        ':price' => $price,
        ':availability' => $availability
    ]);

    $message = "Menu item added successfully";
}

// Fetch menu items with category name (initial page load or GET search)
$where = [];
$params = [];

// search by item name
if (!empty($_GET['search'])) {
    $where[] = "menu_items.name LIKE :search";
    $params[':search'] = "%" . $_GET['search'] . "%";
}

// Filter by category if selected
if (!empty($_GET['category_id'])) {
    $where[] = "menu_items.category_id = :category_id";
    $params[':category_id'] = $_GET['category_id'];
}

$whereSQL = "";
if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$sql = "SELECT menu_items.*, categories.name AS category_name
        FROM menu_items
        JOIN categories ON menu_items.category_id = categories.id
        $whereSQL
        ORDER BY menu_items.id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

require '../includes/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../assets/admin_css/style.css">
    <title>Add Menu</title>
</head>
<body>
<div style="flex: 1;">
    <form method="post" class="add-menu-form">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
        <h2 style="font-size: 16px; margin-bottom: 10px;">Add Menu Item</h2>
        <div class="add-menu-details">
            <label>Category:</label>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="add-menu-details">
            <label>Item Name:</label>
            <input type="text" name="name" required>
        </div>

        <div class="add-menu-details">
            <label>Price:</label>
            <input type="number" step="0.01" name="price" required>
        </div>
        <div class="add-menu-details">
            <label>Availability:</label>
            <select name="availability" required>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>

        <div class="add-menu-btn">
            <button type="submit">Add Item</button>
        </div>
        <?php if ($message): ?>
            <p style="color:green; text-align:center; margin-top: 10px;"><?= $message ?></p>
        <?php endif; ?>
    </form>

    <div class="category-container">
    <!-- Search input -->
        <input 
            type="text" 
            id="adminSearchInput"
            placeholder="Search menu item..." 
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
            style="outline: none; border: 1px solid black; border-radius: 8px; padding: 6px; width: 250px; margin-bottom: 15px;"
        >

    <table class="category-table">
        <h3 style="font-size: 16px; margin-bottom: 10px;" class="category-heading">All Menu Items</h3>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Availability</th>
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
                <td class="action-icons">
                    <a href="edit_menu_item.php?id=<?= $item['id'] ?>" class="edit-btn">Edit</a>
                    <a href="delete_menu_item.php?id=<?= $item['id'] ?>" class="delete-btn"
                       onclick="return confirm('Delete this item?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("adminSearchInput");
    const tableBody = document.getElementById("menuTableBody");

    let debounceTimer;

    function fetchMenu(searchValue) {
        fetch(`add_menu.php?ajax=1&search=${encodeURIComponent(searchValue)}`)
            .then(response => response.text())
            .then(data => {
                tableBody.innerHTML = data;
            })
            .catch(err => console.error(err));
    }

    // Live search while typing
    searchInput.addEventListener("input", () => {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            fetchMenu(searchInput.value);
        }, 300); // 300ms debounce
    });
});
</script>

<?php require '../includes/footer.php'; ?>
</body>
</html>