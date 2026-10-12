<?php

$servername = getenv('DB_HOST') ?: 'localhost';
$username   = getenv('DB_USER') ?: 'root';
$password   = getenv('DB_PASSWORD') ?: '';
$database   = getenv('DB_NAME') ?: 'roommate.db';
$port       = (int)(getenv('DB_PORT') ?: 3306);

<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = getenv('MYSQLHOST')     ?: 'localhost';
$user = getenv('MYSQLUSER')     ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$db   = getenv('MYSQLDATABASE') ?: 'roommate.db';
$port = getenv('MYSQLPORT')     ?: 3306;

$conn = new mysqli($host, $user, $pass, $db, (int)$port);
$conn->set_charset('utf8mb4');

if ($conn->connect_error) {
    die("Database connection failed.");
}

$conn->set_charset("utf8mb4");

?>