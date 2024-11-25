<?php
// Database configuration
$host = 'mysql.db.mdbgo.com';
$db = 'sadullah2000_cms';
$user = 'sadullah2000_admin';
$pass = 'Admin@123';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Handle any errors that occur during the connection
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
