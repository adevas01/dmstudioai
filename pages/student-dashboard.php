<?php
// Load the database connection.
require_once "config/database.php";

// Load authentication helper functions.
require_once "includes/auth.php";

// Only students can access this page.
requireRoles(["student"]);

// Get logged-in student ID from the session.
$studentId = $_SESSION["user_id"];

// Get student details from database.
$stmt = $pdo->prepare("
    SELECT id, name, email, role, status, created_at
    FROM dm_users
    WHERE id = ?
    AND role = 'student'
    AND deleted_at IS NULL
    LIMIT 1
");

// Run query.
$stmt->execute([$studentId]);

// Store student data.
$student = $stmt->fetch();

// Stop page safely if student is not found.
if (!$student) {
    echo '
    <section class="page-hero">
        <h1>Student Not Found</h1>
        <p>Your student profile could not be found. Please contact your teacher or manager.</p>
    </section>';
    return;
}

// Get assigned tasks for this student.
$tasksStmt = $pdo->prepare("
    SELECT 
        tasks.id,
        tasks.title,
        tasks.description,
        tasks.task_type,
        tasks.recommended_tool,
        tasks.deadline,
        task_assignments.status,
        task_assignments.assigned_at
    FROM task_assignments
    INNER JOIN tasks ON task_assignments.task_id = tasks.id
    WHERE task_assignments.student_id = ?
    ORDER BY task_assignments.assigned_at DESC
");

$tasksStmt->execute([$studentId]);

$assignedTasks = $tasksStmt->fetchAll();

// Student details.
$studentName = $student["name"];
$studentEmail = $student["email"];
$studentStatus = $student["status"];
$studentCreated = date("d M Y", strtotime($student["created_at"]));

// Prototype progress values.
$videoProgress = 75;
$designProgress = 45;
$animationProgress = 30;
$storyProgress = 60;

// Average progress.
$averageProgress = round(($videoProgress + $designProgress + $animationProgress + $storyProgress) / 4);
?>

<section class="dashboard-hero student-portal-hero">
    <h1>Welcome back, <?php echo htmlspecialchars($studentName); ?>!</h1>
    <p>Your personal DM Studio AI learning portal for Digital Media.</p>
</section>

<section class="student-portal-layout">

    <div class="student-portal-card student-profile-card">
        <div class="student-avatar-large">
            <?php echo htmlspecialchars(strtoupper(substr($studentName, 0, 1))); ?>
        </div>

        <div>
            <h2><?php echo htmlspecialchars($studentName); ?></h2>
            <p><?php echo htmlspecialchars($studentEmail); ?></p>

            <span class="status-badge status-<?php echo htmlspecialchars($studentStatus); ?>">
                <?php echo htmlspecialchars(ucfirst($studentStatus)); ?>
            </span>

            <p class="small-muted-text">
                Registered: <?php echo htmlspecialchars($studentCreated); ?>
            </p>
        </div>
    </div>

    <div class="student-portal-card">
        <h2>My Learning Progress</h2>
        <p>Your overall Digital Media progress.</p>

        <div class="big-progress-number">
            <?php echo $averageProgress; ?>%
        </div>

        <div class="mini-progress-bar">
            <div class="mini-progress-fill" style="width: <?php echo $averageProgress; ?>%;"></div>
        </div>

        <p class="small-muted-text">Keep going — you are making good progress.</p>
    </div>

    <div class="student-portal-card large-student-card">
        <h2>Continue Learning</h2>
        <p><strong>Current course:</strong> Video Editing Basics</p>
        <p>Continue your current Digital Media unit and complete your next activity.</p>

        <div class="mini-progress-bar">
            <div class="mini-progress-fill" style="width: <?php echo $videoProgress; ?>%;"></div>
        </div>

        <p><?php echo $videoProgress; ?>% complete</p>

        <a href="index.php?route=lessons&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">Continue Course</button>
        </a>
    </div>

    <div class="student-portal-card large-student-card">
        <h2>Digital Media Skills</h2>
        <p>Track your progress across different creative areas.</p>

        <?php
        $skills = [
            "Video Editing" => $videoProgress,
            "Graphic Design" => $designProgress,
            "Animation" => $animationProgress,
            "Digital Storytelling" => $storyProgress
        ];
        ?>

        <?php foreach ($skills as $skillName => $skillProgress): ?>
            <div class="student-skill-row">
                <span><?php echo htmlspecialchars($skillName); ?></span>
                <strong><?php echo $skillProgress; ?>%</strong>
            </div>

            <div class="mini-progress-bar">
                <div class="mini-progress-fill" style="width: <?php echo $skillProgress; ?>%;"></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="student-portal-card">
        <h2>Today’s Tasks</h2>

        <?php if (empty($assignedTasks)): ?>

            <p>No tasks assigned yet.</p>
            <p class="small-muted-text">
                Your teacher will add activities here when they are ready.
            </p>

        <?php else: ?>

            <div class="student-task-list">

                <?php foreach ($assignedTasks as $task): ?>
                    <div class="student-task-item">

                        <h3><?php echo htmlspecialchars($task["title"]); ?></h3>

                        <p>
                            <?php echo htmlspecialchars($task["description"]); ?>
                        </p>

                        <?php if (!empty($task["recommended_tool"])): ?>
                            <p>
                                <strong>Tool:</strong>
                                <?php echo htmlspecialchars($task["recommended_tool"]); ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($task["deadline"])): ?>
                            <p>
                                <strong>Deadline:</strong>
                                <?php echo htmlspecialchars(date("d M Y", strtotime($task["deadline"]))); ?>
                            </p>
                        <?php endif; ?>

                        <p>
                            <strong>Status:</strong>
                            <?php echo htmlspecialchars(ucfirst($task["status"])); ?>
                        </p>

                        <a href="index.php?route=student-task&id=<?php echo htmlspecialchars($task["id"]); ?>&nav=<?php echo $navToken; ?>">
                            <button class="primary-btn small-btn">Open Task</button>
                        </a>

                    </div>
                <?php endforeach; ?>

            </div>

        <?php endif; ?>
    </div>

    <div class="student-portal-card">
        <h2>AI Learning Assistant</h2>
        <p>Ask for help if you are stuck with a task.</p>

        <input class="student-ai-input" type="text" placeholder="Example: How do I cut a video clip?">

        <button class="primary-btn small-btn">Ask AI</button>
    </div>

    <div class="student-portal-card">
        <h2>Teacher Feedback</h2>
        <p><strong>Latest feedback:</strong></p>
        <p>Good progress this week. Remember to upload your storyboard and check your spelling before submitting.</p>

        <a href="index.php?route=feedback&nav=<?php echo $navToken; ?>">
            <button class="secondary-btn small-btn">View Feedback</button>
        </a>
    </div>

    <div class="student-portal-card">
        <h2>Achievements</h2>

        <div class="achievement-list">
            <span>🏆 First project started</span>
            <span>⭐ 3 lessons completed</span>
            <span>🎬 Video editing badge unlocked</span>
            <span>🎨 Creative learner badge</span>
        </div>
    </div>

    <div class="student-portal-card large-student-card">
        <h2>Recent Activity</h2>

        <div class="activity-timeline">
            <div>
                <strong>Today</strong>
                <p>Opened Video Editing Basics.</p>
            </div>

            <div>
                <strong>Yesterday</strong>
                <p>Completed the project checklist.</p>
            </div>

            <div>
                <strong>This week</strong>
                <p>Unlocked the Video Editing badge.</p>
            </div>
        </div>
    </div>

    <div class="student-portal-card">
        <h2>Quick Actions</h2>

        <div class="quick-action-list">
            <a href="index.php?route=lessons&nav=<?php echo $navToken; ?>">
                <button class="secondary-btn small-btn">View Lessons</button>
            </a>

            <a href="index.php?route=submit-work&nav=<?php echo $navToken; ?>">
                <button class="secondary-btn small-btn">Submit Work</button>
            </a>

            <a href="index.php?route=feedback&nav=<?php echo $navToken; ?>">
                <button class="secondary-btn small-btn">View Feedback</button>
            </a>
        </div>
    </div>

</section>