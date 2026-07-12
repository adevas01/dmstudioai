<?php
// Load the database connection.
require_once "config/database.php";

// Load authentication helper functions.
require_once "includes/auth.php";

// Only students can access this page.
requireRoles(["student"]);

// Get logged-in student ID from the session.
$studentId = (int) $_SESSION["user_id"];

// Get student details from the database.
$stmt = $pdo->prepare("
    SELECT id, name, email, role, status, created_at
    FROM dm_users
    WHERE id = ?
      AND role = 'student'
      AND deleted_at IS NULL
    LIMIT 1
");

$stmt->execute([$studentId]);
$student = $stmt->fetch();

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
    INNER JOIN tasks
        ON task_assignments.task_id = tasks.id
    WHERE task_assignments.student_id = ?
    ORDER BY
        CASE
            WHEN tasks.deadline IS NULL THEN 1
            ELSE 0
        END,
        tasks.deadline ASC,
        task_assignments.assigned_at DESC
");

$tasksStmt->execute([$studentId]);
$assignedTasks = $tasksStmt->fetchAll();

// Student details.
$studentName = $student["name"];
$studentEmail = $student["email"];
$studentStatus = $student["status"];
$studentCreated = date("d M Y", strtotime($student["created_at"]));
$studentInitial = strtoupper(substr(trim($studentName), 0, 1));

// Prototype progress values.
// Replace these with database values later.
$videoProgress = 75;
$designProgress = 45;
$animationProgress = 30;
$storyProgress = 60;

// Average progress.
$averageProgress = (int) round(
    ($videoProgress + $designProgress + $animationProgress + $storyProgress) / 4
);

// Prototype activity values.
$totalActivities = 15;
$completedActivities = (int) round(($averageProgress / 100) * $totalActivities);
$remainingActivities = max(0, $totalActivities - $completedActivities);
$currentStreak = 5;

// Task summary.
$totalTasks = count($assignedTasks);
$openStatuses = ["assigned", "pending", "in_progress"];
$openTaskCount = 0;

foreach ($assignedTasks as $assignedTask) {
    if (in_array(strtolower((string) $assignedTask["status"]), $openStatuses, true)) {
        $openTaskCount++;
    }
}

$nextTask = $assignedTasks[0] ?? null;

// Calculate friendly due-date text.
$nextTaskDueText = "";
$nextTaskDueClass = "task-due-neutral";

if ($nextTask && !empty($nextTask["deadline"])) {
    $today = new DateTime("today");
    $deadlineDate = new DateTime($nextTask["deadline"]);
    $daysUntilDue = (int) $today->diff($deadlineDate)->format("%r%a");

    if ($daysUntilDue < 0) {
        $nextTaskDueText = "Overdue";
        $nextTaskDueClass = "task-due-overdue";
    } elseif ($daysUntilDue === 0) {
        $nextTaskDueText = "Due today";
        $nextTaskDueClass = "task-due-today";
    } elseif ($daysUntilDue === 1) {
        $nextTaskDueText = "Due tomorrow";
        $nextTaskDueClass = "task-due-soon";
    } else {
        $nextTaskDueText = "Due in {$daysUntilDue} days";
        $nextTaskDueClass = $daysUntilDue <= 3
            ? "task-due-soon"
            : "task-due-neutral";
    }
}

// Skills list.
$skills = [
    [
        "name" => "Video Editing",
        "progress" => $videoProgress,
        "icon" => "🎬"
    ],
    [
        "name" => "Graphic Design",
        "progress" => $designProgress,
        "icon" => "🎨"
    ],
    [
        "name" => "Animation",
        "progress" => $animationProgress,
        "icon" => "✨"
    ],
    [
        "name" => "Digital Storytelling",
        "progress" => $storyProgress,
        "icon" => "📖"
    ]
];
?>

<section class="student-app-dashboard">

    <!-- Welcome banner -->
    <section class="student-welcome-banner">
        <div class="student-welcome-copy">
            <p class="student-dashboard-kicker">Your learning dashboard</p>

            <h1>
                Welcome back,
                <span><?php echo htmlspecialchars($studentName); ?>!</span>
                <span class="welcome-wave" aria-hidden="true">👋</span>
            </h1>

            <p class="student-welcome-message">
                <?php if ($openTaskCount === 1): ?>
                    You have 1 task waiting for you today.
                <?php elseif ($openTaskCount > 1): ?>
                    You have <?php echo $openTaskCount; ?> tasks waiting for you.
                <?php else: ?>
                    You are all caught up. Keep building your creative skills.
                <?php endif; ?>
            </p>

            <a
                class="student-primary-action"
                href="index.php?route=lessons&nav=<?php echo htmlspecialchars($navToken); ?>">
                Continue Learning
                <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="student-welcome-art" aria-hidden="true">
            <img
                src="assets/images/alvaro-pixel-assistant.png"
                alt="">
        </div>
    </section>

    <!-- Main dashboard grid -->
    <section class="student-dashboard-grid">

        <!-- Student profile -->
        <article class="student-portal-card student-profile-card">
            <div class="student-profile-main">
                <div class="student-avatar-large">
                    <?php echo htmlspecialchars($studentInitial); ?>
                </div>

                <div class="student-profile-details">
                    <h2><?php echo htmlspecialchars($studentName); ?></h2>
                    <p class="student-email">
                        <?php echo htmlspecialchars($studentEmail); ?>
                    </p>

                    <span class="status-badge status-<?php echo htmlspecialchars($studentStatus); ?>">
                        <?php echo htmlspecialchars(ucfirst($studentStatus)); ?>
                    </span>
                </div>
            </div>

            <div class="student-course-details">
                <p class="student-detail-label">Course</p>
                <p class="student-detail-value">Digital Media Level 1</p>

                <p class="student-detail-label">Member since</p>
                <p class="student-detail-value">
                    <?php echo htmlspecialchars($studentCreated); ?>
                </p>
            </div>

            <div class="student-profile-stats">
                <div class="student-stat-item">
                    <span class="student-stat-icon" aria-hidden="true">📚</span>
                    <span class="student-stat-label">Completed</span>
                    <strong><?php echo $completedActivities; ?></strong>
                </div>

                <div class="student-stat-item">
                    <span class="student-stat-icon" aria-hidden="true">📋</span>
                    <span class="student-stat-label">Remaining</span>
                    <strong><?php echo $remainingActivities; ?></strong>
                </div>

                <div class="student-stat-item">
                    <span class="student-stat-icon" aria-hidden="true">🎯</span>
                    <span class="student-stat-label">Current streak</span>
                    <strong><?php echo $currentStreak; ?> days 🔥</strong>
                </div>
            </div>
        </article>

        <!-- Overall learning progress -->
        <article class="student-portal-card learning-progress-card">
            <div class="student-card-heading">
                <div>
                    <p class="student-card-kicker">Progress overview</p>
                    <h2>My Learning Progress</h2>
                </div>

                <span class="student-heading-icon" aria-hidden="true">📈</span>
            </div>

            <p class="student-progress-summary">
                <?php echo $completedActivities; ?> of
                <?php echo $totalActivities; ?> activities completed
            </p>

            <div class="big-progress-number">
                <?php echo $averageProgress; ?>%
            </div>

            <div
                class="mini-progress-bar"
                role="progressbar"
                aria-valuenow="<?php echo $averageProgress; ?>"
                aria-valuemin="0"
                aria-valuemax="100"
                aria-label="Overall learning progress">
                <div
                    class="mini-progress-fill"
                    style="width: <?php echo $averageProgress; ?>%;">
                </div>
            </div>

            <div class="student-progress-footer">
                <p>Keep going — you are making good progress!</p>

                <a href="index.php?route=progress&nav=<?php echo htmlspecialchars($navToken); ?>">
                    View Progress Details →
                </a>
            </div>
        </article>

        <!-- Continue learning -->
        <article class="student-portal-card continue-learning-card">
            <div class="student-card-heading">
                <h2>📘 Continue Learning</h2>
            </div>

            <p>
                <strong>Current course:</strong>
                Video Editing Basics
            </p>

            <p class="student-card-description">
                Continue your current Digital Media unit and complete your next activity.
            </p>

            <div
                class="mini-progress-bar"
                role="progressbar"
                aria-valuenow="<?php echo $videoProgress; ?>"
                aria-valuemin="0"
                aria-valuemax="100"
                aria-label="Video Editing Basics progress">
                <div
                    class="mini-progress-fill"
                    style="width: <?php echo $videoProgress; ?>%;">
                </div>
            </div>

            <p class="student-progress-label">
                <?php echo $videoProgress; ?>% complete
            </p>

            <a
                class="student-primary-action student-card-action"
                href="index.php?route=lessons&nav=<?php echo htmlspecialchars($navToken); ?>">
                Continue Course
                <span aria-hidden="true">→</span>
            </a>
        </article>

        <!-- Today's task -->
        <article class="student-portal-card today-task-card">
            <div class="student-card-heading">
                <h2>✅ Today’s Task</h2>
            </div>

            <?php if (!$nextTask): ?>

                <div class="student-empty-state">
                    <div class="student-empty-icon" aria-hidden="true">🎉</div>
                    <h3>No tasks assigned yet</h3>
                    <p>Your teacher will add activities here when they are ready.</p>
                </div>

            <?php else: ?>

                <div class="featured-task-card">
                    <div class="featured-task-content">
                        <div class="featured-task-topline">
                            <h3><?php echo htmlspecialchars($nextTask["title"]); ?></h3>

                            <?php if ($nextTaskDueText !== ""): ?>
                                <span class="task-due-badge <?php echo $nextTaskDueClass; ?>">
                                    <?php echo htmlspecialchars($nextTaskDueText); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <p><?php echo htmlspecialchars($nextTask["description"]); ?></p>

                        <?php if (!empty($nextTask["recommended_tool"])): ?>
                            <p>
                                <strong>Tool:</strong>
                                <?php echo htmlspecialchars($nextTask["recommended_tool"]); ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($nextTask["deadline"])): ?>
                            <p>
                                <strong>Due date:</strong>
                                <?php echo htmlspecialchars(
                                    date("d M Y", strtotime($nextTask["deadline"]))
                                ); ?>
                            </p>
                        <?php endif; ?>

                        <p>
                            <strong>Status:</strong>
                            <?php echo htmlspecialchars(ucfirst($nextTask["status"])); ?>
                        </p>

                        <a
                            class="student-primary-action student-card-action"
                            href="index.php?route=student-task&id=<?php echo (int) $nextTask["id"]; ?>&nav=<?php echo htmlspecialchars($navToken); ?>">
                            Start Task
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>

                <?php if ($totalTasks > 1): ?>
                    <a
                        class="student-text-link"
                        href="index.php?route=my-tasks&nav=<?php echo htmlspecialchars($navToken); ?>">
                        View all <?php echo $totalTasks; ?> assigned tasks →
                    </a>
                <?php endif; ?>

            <?php endif; ?>
        </article>

        <!-- Digital Media skills -->
        <article class="student-portal-card digital-skills-card">
            <div class="student-card-heading">
                <div>
                    <h2>📊 Digital Media Skills</h2>
                    <p>Track your progress across different creative areas.</p>
                </div>
            </div>

            <div class="student-skills-list">
                <?php foreach ($skills as $skill): ?>
                    <div class="student-skill-item">
                        <div class="student-skill-row">
                            <span>
                                <span class="student-skill-icon" aria-hidden="true">
                                    <?php echo $skill["icon"]; ?>
                                </span>

                                <?php echo htmlspecialchars($skill["name"]); ?>
                            </span>

                            <strong><?php echo (int) $skill["progress"]; ?>%</strong>
                        </div>

                        <div
                            class="mini-progress-bar"
                            role="progressbar"
                            aria-valuenow="<?php echo (int) $skill["progress"]; ?>"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            aria-label="<?php echo htmlspecialchars($skill["name"]); ?> progress">
                            <div
                                class="mini-progress-fill"
                                style="width: <?php echo (int) $skill["progress"]; ?>%;">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <a
                class="student-text-link"
                href="index.php?route=progress&nav=<?php echo htmlspecialchars($navToken); ?>">
                View all skills →
            </a>
        </article>

        <!-- AI assistant -->
        <article class="student-portal-card ai-assistant-card">
            <div class="ai-assistant-content">
                <div class="ai-assistant-main">
                    <div class="student-card-heading">
                        <div>
                            <h2>🤖 AI Learning Assistant</h2>
                            <p>Ask for help with your Digital Media work.</p>
                        </div>
                    </div>

                    <div class="ai-question-row">
                        <textarea
                            id="aiQuestion"
                            class="student-ai-input"
                            placeholder="Example: How do I cut a video clip?"
                            aria-label="Ask the AI Learning Assistant a question"></textarea>

                        <button
                            id="aiButton"
                            class="student-ai-send-button"
                            type="button"
                            onclick="askAI()"
                            aria-label="Send question to AI">
                            ➤
                        </button>
                    </div>

                    <div class="ai-suggestion-section">
                        <p>Try one of these questions:</p>

                        <div class="ai-suggestion-list">
                            <button
                                class="ai-suggestion-chip"
                                type="button"
                                data-question="How do I edit a video?">
                                How do I edit a video?
                            </button>

                            <button
                                class="ai-suggestion-chip"
                                type="button"
                                data-question="Help me understand animation.">
                                Help me understand animation
                            </button>

                            <button
                                class="ai-suggestion-chip"
                                type="button"
                                data-question="How do I upload my work?">
                                How do I upload my work?
                            </button>
                        </div>
                    </div>

                    <div
                        id="aiAnswer"
                        class="ai-answer-box"
                        aria-live="polite">
                    </div>
                </div>

                <div class="ai-assistant-character">
                    <img
                        src="assets/images/alvaro-pixel-assistant.png"
                        alt="Alvaro and Pixel AI learning assistant">
                </div>
            </div>
        </article>

        <!-- Teacher feedback -->
        <article class="student-portal-card teacher-feedback-card">
            <div class="student-card-heading">
                <h2>💬 Teacher Feedback</h2>
            </div>

            <p><strong>Latest feedback:</strong></p>

            <p class="student-card-description">
                Good progress this week. Remember to upload your storyboard and
                check your spelling before submitting.
            </p>

            <a
                class="student-secondary-action"
                href="index.php?route=feedback&nav=<?php echo htmlspecialchars($navToken); ?>">
                View Feedback
                <span aria-hidden="true">→</span>
            </a>
        </article>

        <!-- Achievements -->
        <article class="student-portal-card achievements-card">
            <div class="student-card-heading">
                <h2>🏆 Achievements</h2>
            </div>

            <div class="achievement-list">
                <span>🏆 First project started</span>
                <span>⭐ 3 lessons completed</span>
                <span>🎬 Video editing badge unlocked</span>
                <span>🎨 Creative learner badge</span>
            </div>

            <a
                class="student-text-link"
                href="index.php?route=achievements&nav=<?php echo htmlspecialchars($navToken); ?>">
                View all achievements →
            </a>
        </article>

        <!-- Recent activity -->
        <article class="student-portal-card recent-activity-card large-student-card">
            <div class="student-card-heading">
                <h2>🕒 Recent Activity</h2>
            </div>

            <div class="activity-timeline">
                <div class="activity-item">
                    <span class="activity-icon" aria-hidden="true">📄</span>
                    <div>
                        <strong>Today</strong>
                        <p>Opened Video Editing Basics.</p>
                    </div>
                </div>

                <div class="activity-item">
                    <span class="activity-icon" aria-hidden="true">✅</span>
                    <div>
                        <strong>Yesterday</strong>
                        <p>Completed the project checklist.</p>
                    </div>
                </div>

                <div class="activity-item">
                    <span class="activity-icon" aria-hidden="true">🏅</span>
                    <div>
                        <strong>This week</strong>
                        <p>Unlocked the Video Editing badge.</p>
                    </div>
                </div>
            </div>
        </article>

        <!-- Quick actions -->
        <article class="student-portal-card quick-actions-card large-student-card">
            <div class="student-card-heading">
                <h2>⚡ Quick Actions</h2>
            </div>

            <div class="quick-action-list">
                <a href="index.php?route=lessons&nav=<?php echo htmlspecialchars($navToken); ?>">
                    📘 View Lessons
                </a>

                <a href="index.php?route=my-tasks&nav=<?php echo htmlspecialchars($navToken); ?>">
                    ✅ My Tasks
                </a>

                <a href="index.php?route=submit-work&nav=<?php echo htmlspecialchars($navToken); ?>">
                    ⬆️ Submit Work
                </a>

                <a href="index.php?route=feedback&nav=<?php echo htmlspecialchars($navToken); ?>">
                    💬 View Feedback
                </a>

                <a href="#aiQuestion">
                    🤖 AI Assistant
                </a>
            </div>
        </article>

    </section>
</section>

<script src="assets/js/ai.helper.js?v=10"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const aiQuestion = document.getElementById("aiQuestion");
    const suggestionButtons = document.querySelectorAll(".ai-suggestion-chip");

    suggestionButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            if (!aiQuestion) {
                return;
            }

            aiQuestion.value = button.dataset.question || "";
            aiQuestion.focus();
        });
    });
});
</script>
