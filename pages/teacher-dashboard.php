<?php
// Load the database connection.
require_once "config/database.php";

// Load authentication helper functions.
require_once "includes/auth.php";

// Only teachers, managers, and the owner can access this dashboard.
requireRoles(["teacher", "manager", "owner"]);

// Get all registered students.
$stmt = $pdo->prepare("
    SELECT id, name, email, status, created_at
    FROM dm_users
    WHERE role = 'student'
    AND deleted_at IS NULL
    ORDER BY name ASC
");

$stmt->execute();
$students = $stmt->fetchAll();

// Count students.
$totalStudents = count($students);
$approvedStudents = 0;
$pendingStudents = 0;
$blockedStudents = 0;

foreach ($students as $student) {
    if ($student["status"] === "approved") {
        $approvedStudents++;
    }

    if ($student["status"] === "pending") {
        $pendingStudents++;
    }

    if ($student["status"] === "blocked") {
        $blockedStudents++;
    }
}

// Get latest student submissions.
$submissionStmt = $pdo->prepare("
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
    WHERE dm_users.deleted_at IS NULL
    ORDER BY dm_submissions.submitted_at DESC
    LIMIT 10
");

$submissionStmt->execute();
$submissions = $submissionStmt->fetchAll();

$totalSubmissions = count($submissions);
?>

<section class="dashboard-hero">
    <h1>Teacher Dashboard</h1>
    <p>Monitor student progress, review submissions, and support Digital Media learners.</p>
</section>

<section class="dashboard-layout">

    <div class="dashboard-card large-card">
        <h2>Class Overview</h2>

        <p><strong>Group:</strong> SEND Level 1 Digital Media</p>
        <p><strong>Registered students:</strong> <?php echo $totalStudents; ?></p>
        <p><strong>Latest submissions:</strong> <?php echo $totalSubmissions; ?></p>
        <p><strong>Current unit:</strong> Video Editing</p>

        <a href="index.php?route=users&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">Manage Students</button>
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Student Accounts</h2>

        <p><strong>Approved:</strong> <?php echo $approvedStudents; ?></p>
        <p><strong>Pending:</strong> <?php echo $pendingStudents; ?></p>
        <p><strong>Blocked:</strong> <?php echo $blockedStudents; ?></p>
    </div>

    <div class="dashboard-card large-card">
        <h2>Student Progress</h2>
        <p>Select a registered student and open their learning profile.</p>

        <?php if (empty($students)): ?>
            <p>No students registered yet.</p>
        <?php else: ?>

            <div class="student-progress-panel">
                <div class="student-select-area">
                    <label for="studentProfileSelect">Choose student</label>

                    <select id="studentProfileSelect" class="student-select">
                        <option value="">Select a student...</option>

                        <?php foreach ($students as $student): ?>
                            <option value="index.php?route=student-profile&id=<?php echo (int) $student["id"]; ?>&nav=<?php echo $navToken; ?>">
                                <?php echo htmlspecialchars($student["name"]); ?> — <?php echo htmlspecialchars(ucfirst($student["status"])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="button" class="primary-btn small-btn" onclick="openStudentProfile()">
                        View Student Profile
                    </button>
                </div>

                <div class="student-progress-info">
                    <h3>Student profile includes</h3>

                    <ul>
                        <li>Student account status</li>
                        <li>Registration date</li>
                        <li>Digital media progress</li>
                        <li>Teacher notes and feedback area</li>
                        <li>Submitted work area</li>
                    </ul>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <div class="dashboard-card large-card">
        <h2>Student Submissions</h2>
        <p>Latest work submitted by students.</p>

        <?php if (empty($submissions)): ?>
            <p>No student submissions yet.</p>
        <?php else: ?>
            <div class="submission-list">
                <?php foreach ($submissions as $submission): ?>
                    <div class="submission-card">
                        <h3><?php echo htmlspecialchars($submission["title"]); ?></h3>

                        <p>
                            <strong>Student:</strong>
                            <?php echo htmlspecialchars($submission["student_name"]); ?>
                            —
                            <?php echo htmlspecialchars($submission["student_email"]); ?>
                        </p>

                        <p>
                            <?php echo htmlspecialchars($submission["description"]); ?>
                        </p>

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

    <div class="dashboard-card">
        <h2>Support Alerts</h2>

        <ul>
            <li><?php echo $pendingStudents; ?> student account(s) waiting for approval</li>
            <li><?php echo $totalSubmissions; ?> recent submission(s) to review</li>
            <li>Check students who need help with planning</li>
        </ul>
    </div>

    <div class="dashboard-card">
        <h2>Create Activity</h2>

        <p>Create a SEND-friendly task with simple steps and success criteria.</p>

        <button class="primary-btn small-btn">Create Task</button>
    </div>

</section>