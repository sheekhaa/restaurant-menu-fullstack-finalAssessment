<?php
require '../includes/functions.php';
require "../config/db.php";
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $role = 'waiter';

    $sql = "INSERT INTO users (username, password, role)
            VALUES (:username, :password, :role)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':password' => $password,
        ':role' => $role
    ]);

    $message = "Waiter account created successfully";
}


$sql = "SELECT id, username FROM users WHERE role = 'waiter' ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$waiters = $stmt->fetchAll(PDO::FETCH_ASSOC);


require '../includes/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LogIn Page</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">

</head>
<body>      

    <form class="waiter-form" method="post">
        <h2 style="font-size: 16px; margin-bottom: 10px;">Create Waiter Account</h2>  
        <div class="waiter-details">
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>

        <div class="waiter-details">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>

        <div class="login-btn"> 
            <button class="btn" type="submit">Create Waiter</button>            
        </div>
        <?php if ($message): ?>
            <p style="color:green; text-align:center; margin-top: 10px;"><?php echo $message; ?></p>
        <?php endif; ?>
    </form>
    <div class="category-container">
    <h3 class="category-heading">Total Waiters: <?= count($waiters) ?></h3>
        <table class="category-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Waiter Name</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($waiters as $waiter): ?>
                <tr>
                    <td><?= $waiter['id'] ?></td>
                    <td><?= htmlspecialchars($waiter['username']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="back-dashboard">
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

<?php require '../includes/footer.php'; ?>

