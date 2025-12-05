<?php

$host = "localhost";
$username = "root";
$password = "19moralesnielmark";
$name = "rejtech";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$name;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Connection failed ' . $e->getMessage());
}
