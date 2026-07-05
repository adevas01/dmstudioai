<?php
require_once "config/database.php";
require_once "includes/auth.php";

requireRoles(["teacher", "manager", "owner"]);

$stmt = $pdo->prepare("
    SELECT id, name, email, status, created_at
    FROM dm_users
    WHERE role = 'student'
    AND deleted_at IS NULL
    ORDER BY name ASC
");

$stmt->execute();
$students = $stmt->fetchAll();
?>

<section class="dashboard-hero">
    <h1>Teacher Dashboard</h1>
    <p>Monitor student progress, support learners, and manage digital media activities.</p>
</section>

<section class="dashboard-layout">

    <div class="dashboard-card large-card">
        <h2>Class Overview</h2>
        <p><strong>Group:</strong> SEND Level 1 Digital Media</p>
        <p><strong>Registered students:</strong> <?php echo count($students); ?></p>
        <p><strong>Current unit:</strong> Video Editing</p>

        <a href="index.php?route=users&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">Manage Students</button>
        </a>
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
                    <h3>What the teacher can see</h3>
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

    <div class="dashboard-card">
        <h2>Support Alerts</h2>
        <ul>
            <li>2 students need help with planning</li>
            <li>1 student has not submitted work</li>
            <li>3 students completed the checklist</li>
        </ul>
    </div>

    <div class="dashboard-card">
        <h2>Create Activity</h2>
        <p>Create a SEND-friendly task with simple steps and success criteria.</p>
        <button class="primary-btn small-btn">Create Task</button>
    </div>

</section>