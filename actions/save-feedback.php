<?php
// Start session.
session_start();

// Load database connection.
require_once "../config/database.php";

// Load authentication helpers.
require_once "../includes/auth.php";

// Only teachers, managers, and owner can save feedback.
requireRoles(["teacher", "manager", "owner"]);

// Only accept POST requests.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php?route=teacher&nav=dmstudioai");
    exit;
}

// Get teacher ID from session.
$teacherId = $_SESSION["user_id"];

// Get form data.
$submissionId = (int) ($_POST["submission_id"] ?? 0);
$feedbackText = trim($_POST["feedback_text"] ?? "");

// Validate data.
if ($submissionId <= 0 || $feedbackText === "") {
    header("Location: ../index.php?route=teacher&nav=dmstudioai");
    exit;
}

// Save feedback.
$stmt = $pdo->prepare("
    INSERT INTO dm_feedback (submission_id, teacher_id, feedback_text)
    VALUES (?, ?, ?)
");

$stmt->execute([
    $submissionId,
    $teacherId,
    $feedbackText
]);

// Mark submission as reviewed.
$updateStmt = $pdo->prepare("
    UPDATE dm_submissions
    SET status = 'reviewed'
    WHERE id = ?
");

$updateStmt->execute([$submissionId]);

// Return to review page.
header("Location: ../index.php?route=review-submission&id=" . $submissionId . "&nav=dmstudioai&message=saved");
exit;