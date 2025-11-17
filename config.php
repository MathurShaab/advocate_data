<?php

define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'advocate_db');


define('DB_USER', 'root');
define('DB_PASS', ''); // put your MySQL password here if you set one

// AES key: must be 32 bytes (for AES-256). Keep this secret.
// Generate with: php -r "echo base64_encode(openssl_random_pseudo_bytes(32));"
define('AES_KEY', base64_decode('replace_this_with_base64_of_32_random_bytes=='));

define('UPLOAD_DIR', __DIR__ . '/uploads/'); // folder inside your project (must be writable)

// Session security (for local dev, you may need to disable secure cookie if not using HTTPS)
ini_set('session.cookie_httponly', 1);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}
session_start();

try {
    $dsn = "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}