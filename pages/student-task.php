<?php
// Load database connection.
require_once "config/database.php";

// Load authentication helper functions.
require_once "includes/auth.php";

// Only students can access this page.
requireRoles(["student"]);

// Get logged-in student ID.
$studentId = $_SESSION["user_id"];

// Get task ID from URL.
$taskId = $_GET["id"] ?? null;

if (!$taskId) {
    header("Location: index.php?route=student&nav=" . $navToken);
    exit;
}

// Get the assigned task for this student.
$stmt = $pdo->prepare("
    SELECT 
        tasks.id,
        tasks.title,
        tasks.task_type,
        tasks.description,
        tasks.instructions,
        tasks.success_criteria,
        tasks.recommended_tool,
        tasks.support_level,
        tasks.deadline,
        task_assignments.status,
        task_assignments.assigned_at
    FROM task_assignments
    INNER JOIN tasks ON task_assignments.task_id = tasks.id
    WHERE task_assignments.student_id = ?
    AND tasks.id = ?
    LIMIT 1
");

$stmt->execute([$studentId, $taskId]);

$task = $stmt->fetch();

if (!$task) {
    echo '
    <section class="task-page-wrapper">
        <div class="task-page-card">
            <h1>Task Not Found</h1>
            <p>This task was not found or it has not been assigned to your account.</p>

            <a href="index.php?route=student&nav=' . htmlspecialchars($navToken) . '">
                <button class="secondary-btn small-btn">Back to Dashboard</button>
            </a>
        </div>
    </section>';
    return;
}

// Recommended tool links.
$toolLinks = [
    "Canva" => "https://www.canva.com/",
    "CapCut" => "https://www.capcut.com/",
    "PowerPoint" => "https://www.office.com/launch/powerpoint",
    "Word" => "https://www.office.com/launch/word",
    "Photopea" => "https://www.photopea.com/",
    "Wick Editor" => "https://www.wickeditor.com/",
    "Tinkercad" => "https://www.tinkercad.com/",
    "VS Code" => "https://vscode.dev/"
];

$toolName = $task["recommended_tool"];
$toolLink = $toolLinks[$toolName] ?? null;
?>

<section class="task-page-wrapper">

    <div class="task-hero-card">
        <p class="task-label">Assigned Activity</p>

        <h1><?php echo htmlspecialchars($task["title"]); ?></h1>

        <p>
            <?php echo htmlspecialchars($task["description"]); ?>
        </p>
    </div>

    <div class="task-page-grid">

        <div class="task-page-card task-main-card">

            <h2>Step-by-Step Instructions</h2>

            <div class="task-content-box">
                <?php echo nl2br(htmlspecialchars($task["instructions"])); ?>
            </div>

            <h2>Success Criteria</h2>

            <div class="task-content-box success-box">
                <?php echo nl2br(htmlspecialchars($task["success_criteria"])); ?>
            </div>

            <div class="task-actions">
                <?php if ($toolLink): ?>
                    <a href="<?php echo htmlspecialchars($toolLink); ?>" target="_blank" rel="noopener noreferrer">
                        <button class="primary-btn small-btn">
                            Open <?php echo htmlspecialchars($toolName); ?>
                        </button>
                    </a>
                <?php endif; ?>

                <a href="index.php?route=submit-work&nav=<?php echo $navToken; ?>">
                    <button class="primary-btn small-btn">Submit Work</button>
                </a>

                <a href="index.php?route=student&nav=<?php echo $navToken; ?>">
                    <button class="secondary-btn small-btn">Back to Dashboard</button>
                </a>
            </div>

        </div>

        <aside class="task-page-card task-side-card">

            <h2>Task Details</h2>

            <div class="task-detail-row">
                <span>Task type</span>
                <strong><?php echo htmlspecialchars(ucfirst($task["task_type"])); ?></strong>
            </div>

            <div class="task-detail-row">
                <span>Tool</span>
                <strong><?php echo htmlspecialchars($task["recommended_tool"]); ?></strong>
            </div>

            <div class="task-detail-row">
                <span>Support level</span>
                <strong><?php echo htmlspecialchars(ucfirst($task["support_level"])); ?></strong>
            </div>

            <div class="task-detail-row">
                <span>Status</span>
                <strong><?php echo htmlspecialchars(ucfirst($task["status"])); ?></strong>
            </div>

            <?php if (!empty($task["deadline"])): ?>
                <div class="task-detail-row">
                    <span>Deadline</span>
                    <strong><?php echo htmlspecialchars(date("d M Y", strtotime($task["deadline"]))); ?></strong>
                </div>
            <?php endif; ?>

        </aside>

    </div>

</section>