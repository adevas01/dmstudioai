<?php
require_once "config/database.php";
require_once "includes/auth.php";

requireRoles(["student"]);

$studentId = $_SESSION["user_id"];

$stmt = $pdo->prepare("
    SELECT 
        dm_feedback.feedback_text,
        dm_feedback.created_at,
        dm_submissions.title AS submission_title,
        dm_submissions.status AS submission_status,
        dm_users.name AS teacher_name
    FROM dm_feedback
    INNER JOIN dm_submissions ON dm_feedback.submission_id = dm_submissions.id
    INNER JOIN dm_users ON dm_feedback.teacher_id = dm_users.id
    WHERE dm_submissions.student_id = ?
    ORDER BY dm_feedback.created_at DESC
");

$stmt->execute([$studentId]);
$feedbackList = $stmt->fetchAll();
?>

<section class="dashboard-hero">
    <h1>Teacher Feedback</h1>
    <p>Review your teacher comments and improve your Digital Media work.</p>
</section>

<section class="student-portal-layout">

    <div class="student-portal-card large-student-card">
        <h2>My Feedback</h2>

        <?php if (empty($feedbackList)): ?>
            <p>No teacher feedback has been added yet.</p>
        <?php else: ?>
            <div class="submission-list">
                <?php foreach ($feedbackList as $feedback): ?>
                    <div class="submission-card">
                        <h3><?php echo htmlspecialchars($feedback["submission_title"]); ?></h3>

                        <p>
                            <strong>Teacher:</strong>
                            <?php echo htmlspecialchars($feedback["teacher_name"]); ?>
                        </p>

                        <p>
                            <strong>Status:</strong>
                            <?php echo htmlspecialchars(ucfirst($feedback["submission_status"])); ?>
                        </p>

                        <p>
                            <strong>Feedback:</strong>
                            <?php echo htmlspecialchars($feedback["feedback_text"]); ?>
                        </p>

                        <p>
                            <strong>Date:</strong>
                            <?php echo date("d M Y H:i", strtotime($feedback["created_at"])); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</section>