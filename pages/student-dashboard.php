<?php
// Load the database connection.
// This gives this page access to the $pdo database object.
require_once "config/database.php";

// Load authentication helper functions.
// This gives this page access to functions such as requireRoles().
require_once "includes/auth.php";

// Only students can access this dashboard.
// Teachers, managers, owners, and visitors should not see the student dashboard.
requireRoles(["student"]);

// Get the logged-in student's ID from the session.
// This was created in actions/login-process.php after successful login.
$studentId = $_SESSION["user_id"];

// Prepare a database query to get the logged-in student's information.
$stmt = $pdo->prepare("
    SELECT id, name, email, role, status, created_at
    FROM dm_users
    WHERE id = ?
    AND role = 'student'
    AND deleted_at IS NULL
    LIMIT 1
");

// Run the query using the logged-in student's ID.
$stmt->execute([$studentId]);

// Store the student record.
$student = $stmt->fetch();

// If the student record cannot be found, stop the page safely.
if (!$student) {
    echo '
    <section class="page-hero">
        <h1>Student Not Found</h1>
        <p>Your student profile could not be found. Please contact your teacher or manager.</p>
    </section>';
    return;
}

// Store student information in clear variables.
$studentName = $student["name"];
$studentEmail = $student["email"];
$studentStatus = $student["status"];
$studentCreated = date("d M Y", strtotime($student["created_at"]));

// Temporary prototype progress values.
// Later these can come from a real progress table.
$videoProgress = 75;
$designProgress = 45;
$animationProgress = 30;
$storyProgress = 60;

// Calculate an average progress score.
$averageProgress = round(($videoProgress + $designProgress + $animationProgress + $storyProgress) / 4);
?>

<!-- Student dashboard welcome banner -->
<section class="dashboard-hero student-portal-hero">
    <h1>Welcome back, <?php echo htmlspecialchars($studentName); ?>!</h1>
    <p>Your personal DM Studio AI learning portal for Digital Media.</p>
</section>

<!-- Main student portal layout -->
<section class="student-portal-layout">

    <!-- Student identity/profile card -->
    <div class="student-portal-card student-profile-card">
        <div class="student-avatar-large">
            <?php echo strtoupper(substr($studentName, 0, 1)); ?>
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

    <!-- Overall progress card -->
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

    <!-- Current course card -->
    <div class="student-portal-card large-student-card">
        <h2>Continue Learning</h2>
        <p><strong>Current course:</strong> Video Editing Basics</p>
        <p>Continue your current Digital Media unit and complete your next activity.</p>

        <div class="mini-progress-bar">
            <div class="mini-progress-fill" style="width: <?php echo $videoProgress; ?>%;"></div>
        </div>

        <p><?php echo $videoProgress; ?>% complete</p>

        <button class="primary-btn small-btn">Continue Course</button>
    </div>

    <!-- Course progress breakdown -->
    <div class="student-portal-card large-student-card">
        <h2>Digital Media Skills</h2>
        <p>Track your progress across different creative areas.</p>

        <div class="student-skill-row">
            <span>Video Editing</span>
            <strong><?php echo $videoProgress; ?>%</strong>
        </div>
        <div class="mini-progress-bar">
            <div class="mini-progress-fill" style="width: <?php echo $videoProgress; ?>%;"></div>
        </div>

        <div class="student-skill-row">
            <span>Graphic Design</span>
            <strong><?php echo $designProgress; ?>%</strong>
        </div>
        <div class="mini-progress-bar">
            <div class="mini-progress-fill" style="width: <?php echo $designProgress; ?>%;"></div>
        </div>

        <div class="student-skill-row">
            <span>Animation</span>
            <strong><?php echo $animationProgress; ?>%</strong>
        </div>
        <div class="mini-progress-bar">
            <div class="mini-progress-fill" style="width: <?php echo $animationProgress; ?>%;"></div>
        </div>

        <div class="student-skill-row">
            <span>Digital Storytelling</span>
            <strong><?php echo $storyProgress; ?>%</strong>
        </div>
        <div class="mini-progress-bar">
            <div class="mini-progress-fill" style="width: <?php echo $storyProgress; ?>%;"></div>
        </div>
    </div>

    <!-- Today's tasks card -->
    <div class="student-portal-card">
        <h2>Today’s Tasks</h2>

        <ul class="student-task-list">
            <li>Watch the video editing tutorial</li>
            <li>Complete the project checklist</li>
            <li>Upload one short video draft</li>
        </ul>
    </div>

    <!-- AI learning assistant card -->
    <div class="student-portal-card">
        <h2>AI Learning Assistant</h2>
        <p>Ask for help if you are stuck with a task.</p>

        <input class="student-ai-input" type="text" placeholder="Example: How do I cut a video clip?">

        <button class="primary-btn small-btn">Ask AI</button>
    </div>

    <!-- Teacher feedback card -->
    <div class="student-portal-card">
        <h2>Teacher Feedback</h2>
        <p><strong>Latest feedback:</strong></p>
        <p>Good progress this week. Remember to upload your storyboard and check your spelling before submitting.</p>
    </div>

    <!-- Achievements card -->
    <div class="student-portal-card">
        <h2>Achievements</h2>

        <div class="achievement-list">
            <span>🏆 First project started</span>
            <span>⭐ 3 lessons completed</span>
            <span>🎬 Video editing badge unlocked</span>
            <span>🎨 Creative learner badge</span>
        </div>
    </div>

    <!-- Recent activity card -->
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

    <!-- Quick actions card -->
    <div class="student-portal-card">
        <h2>Quick Actions</h2>

        <div class="quick-action-list">
            <button class="secondary-btn small-btn">View Lessons</button>
            <button class="secondary-btn small-btn">Submit Work</button>
            <button class="secondary-btn small-btn">View Feedback</button>
        </div>
    </div>

</section>
