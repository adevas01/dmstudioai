<?php
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: index.php?route=login&nav=" . $navToken);
    exit;
}

require_once "config/database.php";

$teacher_id = $_SESSION["user_id"];
$task_id = $_GET["id"] ?? null;

if (!$task_id) {
    header("Location: index.php?route=view-tasks&nav=" . $navToken);
    exit;
}

// Get the task and make sure it belongs to this teacher
$stmt = $pdo->prepare("
    SELECT *
    FROM tasks
    WHERE id = :task_id
    AND teacher_id = :teacher_id
");

$stmt->execute([
    ":task_id" => $task_id,
    ":teacher_id" => $teacher_id
]);

$task = $stmt->fetch();

if (!$task) {
    echo "
    <section class='page-section'>
        <div class='page-card'>
            <h1>Task not found</h1>
            <p>This task does not exist or you do not have permission to assign it.</p>
        </div>
    </section>";
    return;
}

// Get approved students from your real users table: dm_users
$studentsStmt = $pdo->prepare("
    SELECT id, name, email
    FROM dm_users
    WHERE role = 'student'
    AND status = 'approved'
    ORDER BY name ASC
");

$studentsStmt->execute();

$students = $studentsStmt->fetchAll();
?>

<section class="page-section">
    <div class="page-card task-create-card">

        <h1>Assign Task</h1>

        <p class="page-intro">
            Choose which student should receive this task.
        </p>

        <div class="task-preview-box">
            <h2><?php echo htmlspecialchars($task["title"]); ?></h2>

            <p>
                <?php echo htmlspecialchars($task["description"]); ?>
            </p>

            <p>
                <strong>Tool:</strong>
                <?php echo htmlspecialchars($task["recommended_tool"]); ?>
            </p>

            <?php if (!empty($task["deadline"])): ?>
                <p>
                    <strong>Deadline:</strong>
                    <?php echo htmlspecialchars($task["deadline"]); ?>
                </p>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET["assigned"]) && $_GET["assigned"] === "success"): ?>
            <div class="alert-success">
                Task assigned successfully.
            </div>
        <?php endif; ?>

        <?php if (empty($students)): ?>

            <p>No approved students found.</p>

        <?php else: ?>

            <form action="actions/assign-task-process.php" method="POST" class="task-form">

                <input 
                    type="hidden" 
                    name="task_id" 
                    value="<?php echo htmlspecialchars($task["id"]); ?>"
                >

                <label for="student_id">Select Student</label>

                <select id="student_id" name="student_id" required>
                    <option value="">Choose student</option>

                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo htmlspecialchars($student["id"]); ?>">
                            <?php echo htmlspecialchars($student["name"]); ?>
                            — <?php echo htmlspecialchars($student["email"]); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="task-form-actions">
                    <button type="submit" class="top-btn">Assign to Student</button>

                    <a href="index.php?route=view-tasks&nav=<?php echo $navToken; ?>" class="secondary-link">
                        Back to My Tasks
                    </a>
                </div>

            </form>

        <?php endif; ?>

    </div>
</section>