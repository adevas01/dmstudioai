<?php
// Load database connection.
require_once "config/database.php";

// Load authentication helpers.
require_once "includes/auth.php";

// Only teachers, managers, and owner can review submissions.
requireRoles(["teacher", "manager", "owner"]);

// Get submission ID from URL.
$submissionId = (int) ($_GET["id"] ?? 0);

// Stop if no valid submission was selected.
if ($submissionId <= 0) {
    echo '
    <section class="page-hero">
        <h1>Submission Not Found</h1>
        <p>No valid submission was selected.</p>
    </section>';
    return;
}

// Get submission and student details.
$stmt = $pdo->prepare("
    SELECT 
        dm_submissions.id,
        dm_submissions.title,
        dm_submissions.description,
        dm_submissions.file_name,
        dm_submissions.file_path,
        dm_submissions.status,
        dm_submissions.submitted_at,
        dm_users.name AS student_name,
        dm_users.email AS student_email
    FROM dm_submissions
    INNER JOIN dm_users ON dm_submissions.student_id = dm_users.id
    WHERE dm_submissions.id = ?
    LIMIT 1
");

$stmt->execute([$submissionId]);
$submission = $stmt->fetch();

// Stop if submission does not exist.
if (!$submission) {
    echo '
    <section class="page-hero">
        <h1>Submission Not Found</h1>
        <p>This submission could not be found.</p>
    </section>';
    return;
}

// Get previous feedback for this submission.
$feedbackStmt = $pdo->prepare("
    SELECT dm_feedback.feedback_text, dm_feedback.created_at, dm_users.name AS teacher_name
    FROM dm_feedback
    INNER JOIN dm_users ON dm_feedback.teacher_id = dm_users.id
    WHERE dm_feedback.submission_id = ?
    ORDER BY dm_feedback.created_at DESC
");

$feedbackStmt->execute([$submissionId]);
$feedbackList = $feedbackStmt->fetchAll();
?>

<section class="dashboard-hero">
    <h1>Review Submission</h1>
    <p>Read the student work and give helpful feedback.</p>
</section>

<section class="student-portal-layout">

    <div class="student-portal-card large-student-card">
        <h2><?php echo htmlspecialchars($submission["title"]); ?></h2>

        <p><strong>Student:</strong> <?php echo htmlspecialchars($submission["student_name"]); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($submission["student_email"]); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($submission["status"])); ?></p>
        <p><strong>Submitted:</strong> <?php echo date("d M Y H:i", strtotime($submission["submitted_at"])); ?></p>

        <p><strong>Description:</strong></p>
        <p><?php echo htmlspecialchars($submission["description"]); ?></p>

        <?php if (!empty($submission["file_path"])): ?>
            <a href="<?php echo htmlspecialchars($submission["file_path"]); ?>" target="_blank">
                <button class="primary-btn small-btn">Open Uploaded File</button>
            </a>
        <?php endif; ?>
    </div>

    <div class="student-portal-card large-student-card">
        <h2>Write Feedback</h2>

        <?php if (isset($_GET["message"]) && $_GET["message"] === "saved"): ?>
            <div class="success-message">Feedback saved successfully.</div>
        <?php endif; ?>

        <form class="auth-form" action="actions/save-feedback.php" method="POST">
            <input type="hidden" name="submission_id" value="<?php echo (int) $submission["id"]; ?>">

            <label>Feedback for student</label>
            <textarea name="feedback_text" required placeholder="Write clear, supportive feedback for the student."></textarea>

            <button type="submit" class="primary-btn">Save Feedback</button>
        </form>
    </div>

    <div class="student-portal-card large-student-card">
        <h2>Previous Feedback</h2>

        <?php if (empty($feedbackList)): ?>
            <p>No feedback has been added yet.</p>
        <?php else: ?>
            <div class="submission-list">
                <?php foreach ($feedbackList as $feedback): ?>
                    <div class="submission-card">
                        <p><?php echo htmlspecialchars($feedback["feedback_text"]); ?></p>
                        <p>
                            <strong>By:</strong>
                            <?php echo htmlspecialchars($feedback["teacher_name"]); ?>
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