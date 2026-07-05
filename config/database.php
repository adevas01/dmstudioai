<?php
// DM Studio AI - Database Connection

$host = "localhost";
$dbname = "debaspwk_dmstudioai";
$username = "debaspwk_dmuser";
$password = "X5iejqTf693312!";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Show errors during development
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Return results as associative arrays
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}