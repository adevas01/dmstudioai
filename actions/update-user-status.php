<?php
session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

requireRoles(["teacher", "manager", "owner"]);

$currentUserId = (int) $_SESSION["user_id"];
$currentRole = $_SESSION["role"];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php?route=users&nav=dmstudioai");
    exit;
}

$userId = (int) ($_POST["user_id"] ?? 0);
$newStatus = $_POST["status"] ?? "";

$allowedStatuses = ["pending", "approved", "blocked"];

if ($userId <= 0 || !in_array($newStatus, $allowedStatuses)) {
    die("Invalid request.");
}

// Get target user
$stmt = $pdo->prepare("
    SELECT id, role
    FROM dm_users
    WHERE id = ?
    AND deleted_at IS NULL
    LIMIT 1
");
$stmt->execute([$userId]);
$targetUser = $stmt->fetch();

if (!$targetUser) {
    die("User not found.");
}

$targetRole = $targetUser["role"];

// Nobody manages the owner here
if ($targetRole === "owner") {
    die("The owner account cannot be managed here.");
}

// User cannot update himself
if ($currentUserId === $userId) {
    die("You cannot change your own status.");
}

// Permission check
if (!canManageUser($currentRole, $targetRole)) {
    die("Access denied. You do not have permission to manage this user.");
}

// Update status
$updateStmt = $pdo->prepare("
    UPDATE dm_users
    SET status = ?
    WHERE id = ?
");
$updateStmt->execute([$newStatus, $userId]);

header("Location: ../index.php?route=users&nav=dmstudioai&message=updated");
exit;