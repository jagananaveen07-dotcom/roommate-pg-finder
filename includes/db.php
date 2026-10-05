<?php

$servername = getenv('DB_HOST') ?: 'localhost';
$username   = getenv('DB_USER') ?: 'root';
$password   = getenv('DB_PASSWORD') ?: '';
$database   = getenv('DB_NAME') ?: 'roommate.db';
$port       = (int)(getenv('DB_PORT') ?: 3306);

$conn = new mysqli(
    $servername,
    $username,
    $password,
    $database,
    $port
);

if ($conn->connect_error) {
    die("Database connection failed.");
}

$conn->set_charset("utf8mb4");

?>