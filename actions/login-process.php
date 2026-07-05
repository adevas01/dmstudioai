<?php
session_start();

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php?route=login&nav=dmstudioai");
    exit;
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if (empty($email) || empty($password)) {
    header("Location: ../index.php?route=login&nav=dmstudioai&error=empty");
    exit;
}

$stmt = $pdo->prepare("
    SELECT * FROM dm_users
    WHERE email = ?
    AND deleted_at IS NULL
    LIMIT 1
");

$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user["password"])) {
    header("Location: ../index.php?route=login&nav=dmstudioai&error=invalid");
    exit;
}

if ($user["status"] === "pending") {
    header("Location: ../index.php?route=login&nav=dmstudioai&error=pending");
    exit;
}

if ($user["status"] === "blocked") {
    header("Location: ../index.php?route=login&nav=dmstudioai&error=blocked");
    exit;
}

// Save user session
$_SESSION["user_id"] = $user["id"];
$_SESSION["name"] = $user["name"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];

// Redirect by role
if ($user["role"] === "student") {
    header("Location: ../index.php?route=student&nav=dmstudioai");
    exit;
}

if ($user["role"] === "teacher") {
    header("Location: ../index.php?route=teacher&nav=dmstudioai");
    exit;
}

if ($user["role"] === "manager") {
    header("Location: ../index.php?route=manager&nav=dmstudioai");
    exit;
}

if ($user["role"] === "owner") {
    header("Location: ../index.php?route=owner&nav=dmstudioai");
    exit;
}

header("Location: ../index.php?route=home&nav=dmstudioai");
exit;