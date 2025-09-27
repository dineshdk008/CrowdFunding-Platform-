<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'crowdfund_db');
define('DB_USER', 'root');
define('DB_PASS', '');


try {
$pdo = new PDO(
"mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
DB_USER,
DB_PASS,
[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
} catch (PDOException $e) {
http_response_code(500);
echo "Database connection failed: " . $e->getMessage();
exit;
}