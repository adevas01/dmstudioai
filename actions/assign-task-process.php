<?php
session_start();

require_once "../config/database.php";

if (!isset($pdo)) {
    die("Database connection error.");
}

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: ../index.php?route=login&nav=dmstudioai");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php?route=view-tasks&nav=dmstudioai");
    exit;
}

$teacher_id = $_SESSION["user_id"];
$task_id = $_POST["task_id"] ?? null;
$student_id = $_POST["student_id"] ?? null;

if (!$task_id || !$student_id) {
    header("Location: ../index.php?route=view-tasks&nav=dmstudioai&error=missing");
    exit;
}

try {
    // Make sure the task belongs to this teacher.
    $checkTask = $pdo->prepare("
        SELECT id
        FROM tasks
        WHERE id = :task_id
        AND teacher_id = :teacher_id
    ");

    $checkTask->execute([
        ":task_id" => $task_id,
        ":teacher_id" => $teacher_id
    ]);

    if (!$checkTask->fetch()) {
        die("You are not allowed to assign this task.");
    }

    // Prevent duplicate assignment.
    $checkAssignment = $pdo->prepare("
        SELECT id
        FROM task_assignments
        WHERE task_id = :task_id
        AND student_id = :student_id
    ");

    $checkAssignment->execute([
        ":task_id" => $task_id,
        ":student_id" => $student_id
    ]);

    if ($checkAssignment->fetch()) {
        header("Location: ../index.php?route=assign-task&id=" . $task_id . "&nav=dmstudioai&assigned=success");
        exit;
    }

    // Assign task.
    $stmt = $pdo->prepare("
        INSERT INTO task_assignments
        (task_id, student_id, status)
        VALUES
        (:task_id, :student_id, 'assigned')
    ");

    $stmt->execute([
        ":task_id" => $task_id,
        ":student_id" => $student_id
    ]);

    header("Location: ../index.php?route=assign-task&id=" . $task_id . "&nav=dmstudioai&assigned=success");
    exit;

} catch (PDOException $e) {
    die("Error assigning task: " . $e->getMessage());
}