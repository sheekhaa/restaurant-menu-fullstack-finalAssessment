<?php 
session_start();
require "../config/db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "SELECT * FROM users WHERE username = :username AND role = :role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':role' => $role
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && md5($password) === $user['password']) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($role == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: ../waiter/waiter_dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid username, password or role";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="../assets/admin_css/style.css">
</head>
<body>
<form method="POST" class="admin-form">
    <h3>Admin Login</h3>    

    <div class="admin-details">
        <label>Username:</label>
        <input type="text" name="username" required>
    </div>

    <div class="admin-details">
        <label>Password:</label>
        <input type="password" name="password" required>
    </div>

    <div class="admin-details">
        <label>Role:</label>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="waiter">Waiter</option>
        </select>
    </div>

    <div class="login-btn">
        <button class="btn" type="submit">Log In</button>
    </div>
    <?php if ($error): ?>
        <p style="color:red; text-align:center; margin-top: 10px"><?php echo $error; ?></p>
    <?php endif; ?>
</form>

</body>
</html>
