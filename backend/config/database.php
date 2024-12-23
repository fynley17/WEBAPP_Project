<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ );
$dotenv->load();

$host = $_ENV['DB_HOST'];
$name = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

try {
    $dsn = "mysql:host=$host;dbname=$name";
    $pdo = new PDO( $dsn, $user,$password );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    // echo"connection successful";
} catch (PDOException $e) {
    echo "connection failed". $e->getMessage();
}

return $pdo;