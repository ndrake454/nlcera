<?php
// includes/db.php - Database Connection using PDO

require_once __DIR__ . '/../config.php';

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
     $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
     // In production, log this error instead of displaying it
     // error_log('Database Connection Error: ' . $e->getMessage());
     // Display a generic error message to the user
     die('Sorry, a database connection error occurred. Please try again later.');
     // For Development:
     // throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>