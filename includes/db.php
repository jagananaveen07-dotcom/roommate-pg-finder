<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = getenv('MYSQLHOST')     ?: getenv('DB_HOST')     ?: 'localhost';
$user = getenv('MYSQLUSER')     ?: getenv('DB_USER')     ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: getenv('DB_PASSWORD') ?: '';
$db   = getenv('MYSQLDATABASE') ?: getenv('DB_NAME')     ?: 'roommate.db';
$port = (int)(getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: 3306);

$conn = new mysqli($host, $user, $pass, $db, $port);
$conn->set_charset('utf8mb4');