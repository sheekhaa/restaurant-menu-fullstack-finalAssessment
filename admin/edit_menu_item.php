<?php
session_start();
require "../config/db.php";
// Restrict access to admin users only
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    die("Access denied");
}
// Check if menu item ID is provided via GET
if (!isset($_GET['id'])) {
    die("Menu item ID missing");
}

$id = $_GET['id'];
$message = "";

// Fetch menu item
$sql = "SELECT * FROM menu_items WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("Menu item not found");
}

// Fetch categories for dropdown
$sql = "SELECT * FROM categories ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for updating menu item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];
    // Update menu item in database
    $sql = "UPDATE menu_items 
            SET category_id = :category_id,
                name = :name,
                price = :price,
                availability = :availability
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':category_id' => $category_id,
        ':name' => $name,
        ':price' => $price,
        ':availability' => $availability,
        ':id' => $id
    ]);

    $message = "Menu item updated successfully";

    // refresh data
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
}

require '../includes/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Menu Item</title>
    <link rel="stylesheet" type="text/css" href="../assets/admin_css/style.css">
</head>
<body>

<form method="post" class="edit-form">
    <h2 style="font-size:16px; margin-bottom: 10px;">Edit Menu Item</h2>

    <div class="edit-details">
        <label>Category:</label>
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"
                    <?= $cat['id'] == $item['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="edit-details">
        <label>Item Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
    </div>

    <div class="edit-details">
        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?= $item['price'] ?>" required>
    </div>

    <div class="edit-details">
        <label>Availability:</label>
        <select name="availability">
            <option value="yes" <?= $item['availability']=='yes'?'selected':'' ?>>Yes</option>
            <option value="no" <?= $item['availability']=='no'?'selected':'' ?>>No</option>
        </select>
    </div>

    <div class="edit-btn">
        <button type="submit">Update Item</button>
    </div>

    <?php if ($message): ?>
        <p style="color:green;text-align:center;"><?= $message ?></p>
    <?php endif; ?>
</form>

<!-- <div class="back-dashboard">
    <a href="add_menu.php">Back</a>
</div> -->

</body>
</html>

<?php require '../includes/footer.php'; ?>
