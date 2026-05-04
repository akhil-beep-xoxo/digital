<?php
// DIGITAL SERVICE 24 - Local XAMPP config
// Use this version on your laptop with XAMPP.

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$dbname = "digital_service_24";
$username = "root";
$password = "";

$company_phone = "+91 98765 43210";
$company_email = "digitalservice24@gmail.com";
$whatsapp_number = "919876543210"; // change this to your WhatsApp number: country code + number, no +, no spaces

$admin_user = "admin";
$admin_pass = "admin123";

try {
    // First connect to MySQL server, then create database if missing
    $pdoServer = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    $pdoServer->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Now connect to the project database
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        phone VARCHAR(30) NOT NULL,
        service VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Fix old table if it already exists without service column
    $check = $pdo->query("SHOW COLUMNS FROM contact_messages LIKE 'service'")->fetch();
    if (!$check) {
        $pdo->exec("ALTER TABLE contact_messages ADD service VARCHAR(100) NOT NULL DEFAULT 'General' AFTER phone");
    }
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
?>
