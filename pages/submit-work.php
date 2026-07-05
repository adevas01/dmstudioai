<?php
require_once "config/database.php";
require_once "includes/auth.php";

requireRoles(["student"]);

$studentId = $_SESSION["user_id"];

$stmt = $pdo->prepare("
    SELECT id, title, description, file_name, file_path, status, submitted_at
    FROM dm_submissions
    WHERE student_id = ?
    ORDER BY submitted_at DESC
");

$stmt->execute([$studentId]);
$submissions = $stmt->fetchAll();
?>

<section class="dashboard-hero">
    <h1>Submit Work</h1>
    <p>Upload your Digital Media project for your teacher to review.</p>
</section>

<section class="student-portal-layout">

    <div class="student-portal-card large-student-card">
        <h2>Current Assignment</h2>
        <p><strong>Assignment:</strong> Short Video Draft</p>
        <p>Create and submit a short video using cuts, text, music, and simple effects.</p>
        <p><strong>Due date:</strong> Friday</p>
    </div>

    <div class="student-portal-card large-student-card">
        <h2>Submit Your Work</h2>

        <?php if (isset($_GET["message"]) && $_GET["message"] === "submitted"): ?>
            <div class="success-message">Your work was submitted successfully.</div>
        <?php endif; ?>

        <?php if (isset($_GET["error"])): ?>
            <div class="error-message">
                <?php
                if ($_GET["error"] === "title") echo "Please enter a project title.";
                elseif ($_GET["error"] === "filetype") echo "This file type is not allowed.";
                elseif ($_GET["error"] === "filesize") echo "The file is too large. Maximum size is 50MB.";
                elseif ($_GET["error"] === "upload") echo "The file could not be uploaded.";
                else echo "There was a problem with your submission.";
                ?>
            </div>
        <?php endif; ?>

        <form class="auth-form" action="actions/submit-work-process.php" method="POST" enctype="multipart/form-data">
            <label>Project title</label>
            <input type="text" name="project_title" placeholder="Example: My College Video" required>

            <label>Short description</label>
            <textarea name="project_description" placeholder="Write a short description of your work."></textarea>

            <label>Upload file</label>
            <input type="file" name="project_file">

            <button type="submit" class="primary-btn">Submit Work</button>
        </form>
    </div>

    <div class="student-portal-card large-student-card">
        <h2>My Submissions</h2>

        <?php if (empty($submissions)): ?>
            <p>You have not submitted any work yet.</p>
        <?php else: ?>
            <div class="submission-list">
                <?php foreach ($submissions as $submission): ?>
                    <div class="submission-card">
                        <h3><?php echo htmlspecialchars($submission["title"]); ?></h3>
                        <p><?php echo htmlspecialchars($submission["description"]); ?></p>

                        <p>
                            <strong>Status:</strong>
                            <?php echo htmlspecialchars(ucfirst($submission["status"])); ?>
                        </p>

                        <p>
                            <strong>Submitted:</strong>
                            <?php echo date("d M Y H:i", strtotime($submission["submitted_at"])); ?>
                        </p>

                        <?php if (!empty($submission["file_path"])): ?>
                            <a href="<?php echo htmlspecialchars($submission["file_path"]); ?>" target="_blank">
                                View uploaded file
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</section>