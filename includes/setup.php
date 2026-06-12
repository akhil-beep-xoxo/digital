<?php

$pdo = new PDO('sqlite:database.sqlite');

$pdo->exec("
CREATE TABLE IF NOT EXISTS contact_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    email TEXT,
    phone TEXT,
    service TEXT,
    message TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

echo "Database created!";
