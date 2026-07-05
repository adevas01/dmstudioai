<?php
session_start();

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php?route=register&nav=dmstudioai");
    exit;
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$role = $_POST["role"] ?? "student";

// Only students and teachers can self-register
$allowedRoles = ["student", "teacher"];

if (empty($name) || empty($email) || empty($password)) {
    die("Please complete all fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}

if (!in_array($role, $allowedRoles)) {
    die("Invalid role selected.");
}

if (strlen($password) < 6) {
    die("Password must be at least 6 characters.");
}

// Check if email already exists
$stmt = $pdo->prepare("SELECT id FROM dm_users WHERE email = ? AND deleted_at IS NULL");
$stmt->execute([$email]);
$existingUser = $stmt->fetch();

if ($existingUser) {
    die("This email is already registered.");
}

// Hash password safely
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// New users are pending approval
$status = "pending";

$stmt = $pdo->prepare("
    INSERT INTO dm_users (name, email, password, role, status)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $name,
    $email,
    $hashedPassword,
    $role,
    $status
]);

header("Location: ../index.php?route=login&nav=dmstudioai&message=registered");
exit;