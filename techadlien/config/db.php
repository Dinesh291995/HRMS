<?php
// config/db.php

$host = 'localhost';
$dbname = 'gamapkpd_tad';
$username = 'gamapkpd_tad_admin';
$password = 'qwer#asd#zxc#99';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the database successfully!";
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Could not connect to the database. Please check the logs for more details.");
}

return $pdo;