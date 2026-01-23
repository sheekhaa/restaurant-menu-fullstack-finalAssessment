<?php
require '../includes/functions.php';
require "../config/db.php";
$message = "";

// Fetch categories for dropdown
$sql = "SELECT * FROM categories ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Add menu item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

// Fetch menu items with category name
$sql = "SELECT menu_items.*, categories.name AS category_name
        FROM menu_items
        JOIN categories ON menu_items.category_id = categories.id
        ORDER BY menu_items.id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);


require '../includes/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <title>Add Menu</title>
</head>
<body>
    
    <form method="post" class="add-menu-form">
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
    <div class="back-dashboard">
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>

    <div class="category-container">   

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
        <tbody>
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


</body>
</html>

<?php require '../includes/footer.php'; ?>
