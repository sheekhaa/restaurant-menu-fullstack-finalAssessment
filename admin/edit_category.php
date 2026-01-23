<?php
require '../includes/functions.php';
require "../config/db.php";

if (!isset($_GET['id'])) {
    die("Category ID missing");
}

$id = $_GET['id'];
$message = "";

// Fetch current category
$sql = "SELECT * FROM categories WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("Category not found");
}

// Update category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $sql = "UPDATE categories SET name = :name WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':id' => $id
    ]);
    $message = "Category updated successfully";

    // Refresh data
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Category</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <form method="post" class="edit-form">
        <h2 style="font-size: 16px; margin-bottom: 10px;">Edit Category</h2>
        <div class="edit-details">
            <label>Category Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
        </div>
        <div class="edit-btn">
            <button type="submit">Update Category</button>
        </div>
            <?php if ($message): ?>
                <p style="color:green; text-align:center; margin-top: 10px;"><?= $message ?></p>
            <?php endif; ?>
    </form>    
    <div class="back-dashboard">
         <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>