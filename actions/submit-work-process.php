<?php
session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

requireRoles(["student"]);

$studentId = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php?route=submit-work&nav=dmstudioai");
    exit;
}

$title = trim($_POST["project_title"] ?? "");
$description = trim($_POST["project_description"] ?? "");

if ($title === "") {
    header("Location: ../index.php?route=submit-work&nav=dmstudioai&error=title");
    exit;
}

$fileName = null;
$filePath = null;

if (isset($_FILES["project_file"]) && $_FILES["project_file"]["error"] === UPLOAD_ERR_OK) {
    $uploadDir = "../uploads/submissions/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $originalName = basename($_FILES["project_file"]["name"]);
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    $allowedExtensions = ["jpg", "jpeg", "png", "gif", "pdf", "doc", "docx", "mp4", "mov", "zip"];

    if (!in_array($extension, $allowedExtensions)) {
        header("Location: ../index.php?route=submit-work&nav=dmstudioai&error=filetype");
        exit;
    }

    if ($_FILES["project_file"]["size"] > 50 * 1024 * 1024) {
        header("Location: ../index.php?route=submit-work&nav=dmstudioai&error=filesize");
        exit;
    }

    $safeFileName = "student_" . $studentId . "_" . time() . "." . $extension;
    $targetPath = $uploadDir . $safeFileName;

    if (!move_uploaded_file($_FILES["project_file"]["tmp_name"], $targetPath)) {
        header("Location: ../index.php?route=submit-work&nav=dmstudioai&error=upload");
        exit;
    }

    $fileName = $originalName;
    $filePath = "uploads/submissions/" . $safeFileName;
}

$stmt = $pdo->prepare("
    INSERT INTO dm_submissions (student_id, title, description, file_name, file_path)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $studentId,
    $title,
    $description,
    $fileName,
    $filePath
]);

header("Location: ../index.php?route=submit-work&nav=dmstudioai&message=submitted");
exit;