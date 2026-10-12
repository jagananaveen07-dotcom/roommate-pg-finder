<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Railway MySQL variables first, then your own DB_* names, then local defaults
$host = getenv('MYSQLHOST')     ?: getenv('DB_HOST')     ?: 'localhost';
$user = getenv('MYSQLUSER')     ?: getenv('DB_USER')     ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: getenv('DB_PASSWORD') ?: '';
$db   = getenv('MYSQLDATABASE') ?: getenv('DB_NAME')     ?: 'roommate.db';
$port = (int)(getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: 3306);

try {
    $conn = new mysqli($host, $user, $pass, $db, $port);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    } catch (mysqli_sql_exception $e) {
    die('DB error: ' . $e->getMessage() . ' | host=' . $host . ' | db=' . $db . ' | port=' . $port);
}
