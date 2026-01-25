<?php
session_start();
require '../includes/functions.php';
require "../config/db.php";
$message = "";
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    die("Access denied");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token");
    }
    $name = $_POST['name'];
    $sql = "INSERT INTO categories (name) VALUES (:name)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name
    ]);
    $message = "Category added successfully";
}

// Fetch all categories to display
$sql = "SELECT * FROM categories ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

require '../includes/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Categories</title>
    <link rel="stylesheet" type="text/css" href="../assets/admin_css/style.css">

</head>
<body>

    <form method="post" class="category-form">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
        <h2 style="font-size: 16px; margin-bottom: 10px;">Create Category</h2>
        <div class="category-details">
            <label>Category</label>
            <input type="text" name="name" placeholder="Category name" required>
        </div>
        <div class="category-btn">
            <button type="submit">Add Category</button>
        </div>
        <?php if ($message): ?>
            <p style="color:green; text-align:center; margin-top: 10px;"><?php echo $message; ?></p>
        <?php endif; ?>
    </form>

    <div class="category-container">
    <h3 class="category-heading">All Categories</h3>
        <table class="category-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr class="category-row"> 
                    <td><?= $cat['id'] ?></td>
                    <td><?= htmlspecialchars($cat['name']) ?></td>  
                     <td class="action-icons">
                        <a href="edit_category.php?id=<?= $cat['id'] ?>" class="edit-btn">Edit</a>
                       <a href="delete_category.php?id=<?= $cat['id'] ?>" 
                        class="delete-btn" onclick="return confirm('Delete this item?')">
                        Delete</a>  
                    </td>
      
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<!--     <div class="back-dashboard">
         <a href="admin_dashboard.php">Back to Dashboard</a>
    </div> -->
</body>
</html>
<?php require '../includes/footer.php'; ?>