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

if ($userId <= 0) {
    die("Invalid user selected.");
}

// Nobody can delete themselves
if ($currentUserId === $userId) {
    die("You cannot delete your own account.");
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

// Nobody can delete the owner
if ($targetRole === "owner") {
    die("The owner account cannot be deleted.");
}

// Permission check
if (!canManageUser($currentRole, $targetRole)) {
    die("Access denied. You do not have permission to delete this user.");
}

// Soft delete: safer than permanent delete
$deleteStmt = $pdo->prepare("
    UPDATE dm_users
    SET deleted_at = NOW()
    WHERE id = ?
");
$deleteStmt->execute([$userId]);

header("Location: ../index.php?route=users&nav=dmstudioai&message=deleted");
exit;