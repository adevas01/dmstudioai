<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load database connection.
require_once "../config/database.php";

// Check PDO connection.
if (!isset($pdo)) {
    die("Database connection error: \$pdo variable was not found. Check config/database.php.");
}

// Only teachers can save tasks.
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: ../index.php?route=login&nav=dmstudioai");
    exit;
}

// This page should only receive form submissions.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php?route=create-task&nav=dmstudioai");
    exit;
}

$teacher_id = $_SESSION["user_id"];

$title = trim($_POST["title"] ?? "");
$task_type = trim($_POST["task_type"] ?? "");
$description = trim($_POST["description"] ?? "");
$instructions = trim($_POST["instructions"] ?? "");
$success_criteria = trim($_POST["success_criteria"] ?? "");
$recommended_tool = trim($_POST["recommended_tool"] ?? "");
$support_level = trim($_POST["support_level"] ?? "medium");
$deadline = trim($_POST["deadline"] ?? "");

if ($deadline === "") {
    $deadline = null;
}

if (
    $title === "" ||
    $task_type === "" ||
    $description === "" ||
    $instructions === "" ||
    $success_criteria === ""
) {
    header("Location: ../index.php?route=create-task&nav=dmstudioai&error=missing");
    exit;
}

try {
    $sql = "INSERT INTO tasks 
            (teacher_id, title, task_type, description, instructions, success_criteria, recommended_tool, support_level, deadline)
            VALUES 
            (:teacher_id, :title, :task_type, :description, :instructions, :success_criteria, :recommended_tool, :support_level, :deadline)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":teacher_id" => $teacher_id,
        ":title" => $title,
        ":task_type" => $task_type,
        ":description" => $description,
        ":instructions" => $instructions,
        ":success_criteria" => $success_criteria,
        ":recommended_tool" => $recommended_tool,
        ":support_level" => $support_level,
        ":deadline" => $deadline
    ]);

    header("Location: ../index.php?route=teacher&nav=dmstudioai&task=created");
    exit;

} catch (PDOException $e) {
    die("Error saving task: " . $e->getMessage());
}