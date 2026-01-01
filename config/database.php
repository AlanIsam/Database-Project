<?php
// Database configuration
$host = 'localhost';
$dbname = 'wellness_center';
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>