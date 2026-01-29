<?php
// Database connection using PDO with error handling
$host = "localhost";
$dbname = "np03cs4a240329";
$username = "np03cs4a240329";
$password = "fpSPYznoyt";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Enable exceptions for errors
} catch (PDOException $e) {
    die("Database connection failed");
}
?>
