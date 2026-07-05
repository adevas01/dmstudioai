<?php
require_once "config/database.php";
require_once "includes/auth.php";

requireRoles(["teacher", "manager", "owner"]);

$stmt = $pdo->prepare("
    SELECT id, name, email, status, created_at
    FROM dm_users
    WHERE role = 'student'
    AND deleted_at IS NULL
    ORDER BY created_at DESC
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
        <h2>Registered Students</h2>
        <p>Students registered on DM Studio AI.</p>

        <?php if (empty($students)): ?>
            <p>No students registered yet.</p>
        <?php else: ?>
            <div class="teacher-student-list">
                <?php foreach ($students as $student): ?>
                    <div class="teacher-student-card">
                        <div>
                            <h3><?php echo htmlspecialchars($student["name"]); ?></h3>
                            <p><?php echo htmlspecialchars($student["email"]); ?></p>
                            <small>Registered: <?php echo date("d M Y", strtotime($student["created_at"])); ?></small>
                        </div>

                        <div>
                            <span class="status-badge status-<?php echo htmlspecialchars($student["status"]); ?>">
                                <?php echo htmlspecialchars(ucfirst($student["status"])); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="dashboard-card large-card">
        <h2>Student Progress</h2>
        <p>View registered students and check their learning progress.</p>

        <?php if (empty($students)): ?>
            <p>No students registered yet.</p>
        <?php else: ?>
            <div class="progress-student-list">
                <?php foreach ($students as $student): ?>
                    <div class="progress-student-card">
                        <div class="progress-student-info">
                            <h3><?php echo htmlspecialchars($student["name"]); ?></h3>
                            <p><?php echo htmlspecialchars($student["email"]); ?></p>

                            <span class="status-badge status-<?php echo htmlspecialchars($student["status"]); ?>">
                                <?php echo htmlspecialchars(ucfirst($student["status"])); ?>
                            </span>
                        </div>

                        <div class="student-progress-details">
                            <p><strong>Video Editing:</strong> 75%</p>
                            <div class="mini-progress-bar">
                                <div class="mini-progress-fill" style="width: 75%;"></div>
                            </div>

                            <p><strong>Graphic Design:</strong> 45%</p>
                            <div class="mini-progress-bar">
                                <div class="mini-progress-fill" style="width: 45%;"></div>
                            </div>

                            <p><strong>Animation:</strong> 30%</p>
                            <div class="mini-progress-bar">
                                <div class="mini-progress-fill" style="width: 30%;"></div>
                            </div>

                            <p><strong>Digital Storytelling:</strong> 60%</p>
                            <div class="mini-progress-bar">
                                <div class="mini-progress-fill" style="width: 60%;"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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