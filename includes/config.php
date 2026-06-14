<?php

$admin_user = getenv('ADMIN_USER') ?: 'admin';
$admin_pass = getenv('ADMIN_PASS') ?: 'admin123';

$whatsapp_number = getenv('WHATSAPP_NUMBER') ?: '910000000000';

$dbDir = __DIR__ . '/../data';

if (!is_dir($dbDir)) {
    mkdir($dbDir, 0775, true);
}

$dbFile = $dbDir . '/database.sqlite';

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$pdo->exec("
CREATE TABLE IF NOT EXISTS contact_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    phone TEXT NOT NULL,
    service TEXT NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)
");
