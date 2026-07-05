<?php
require_once "config/database.php";

echo "<h1>Database connection successful ✅</h1>";

$stmt = $pdo->query("SELECT DATABASE() AS current_database");
$result = $stmt->fetch();

echo "<p>Connected to database: " . htmlspecialchars($result["current_database"]) . "</p>";
?>