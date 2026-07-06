<?php
require_once "config/database.php";
require_once "includes/auth.php";

requireRoles(["student"]);

$studentId = $_SESSION["user_id"];

$stmt = $pdo->prepare("
    SELECT 
        tasks.id,
        tasks.title,
        tasks.description,
        tasks.task_type,
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
    ORDER BY task_assignments.assigned_at DESC
");

$stmt->execute([$studentId]);

$tasks = $stmt->fetchAll();
?>

<section class="page-section">
    <div class="page-card">

        <h1>My Tasks</h1>

        <p class="page-intro">
            These are the activities your teacher has assigned to you.
        </p>

        <?php if (empty($tasks)): ?>

            <div class="task-empty-box">
                <h2>No tasks yet</h2>
                <p>Your teacher has not assigned any activities to you yet.</p>

                <a href="index.php?route=student&nav=<?php echo $navToken; ?>">
                    <button class="secondary-btn small-btn">Back to Dashboard</button>
                </a>
            </div>

        <?php else: ?>

            <div class="task-list">

                <?php foreach ($tasks as $task): ?>

                    <div class="task-card">

                        <h2><?php echo htmlspecialchars($task["title"]); ?></h2>

                        <p>
                            <?php echo htmlspecialchars($task["description"]); ?>
                        </p>

                        <p>
                            <strong>Type:</strong>
                            <?php echo htmlspecialchars($task["task_type"]); ?>
                        </p>

                        <p>
                            <strong>Tool:</strong>
                            <?php echo htmlspecialchars($task["recommended_tool"]); ?>
                        </p>

                        <p>
                            <strong>Support:</strong>
                            <?php echo htmlspecialchars(ucfirst($task["support_level"])); ?>
                        </p>

                        <p>
                            <strong>Status:</strong>
                            <?php echo htmlspecialchars(ucfirst($task["status"])); ?>
                        </p>

                        <?php if (!empty($task["deadline"])): ?>
                            <p>
                                <strong>Deadline:</strong>
                                <?php echo htmlspecialchars(date("d M Y", strtotime($task["deadline"]))); ?>
                            </p>
                        <?php endif; ?>

                        <a href="index.php?route=student-task&id=<?php echo htmlspecialchars($task["id"]); ?>&nav=<?php echo $navToken; ?>">
                            <button class="primary-btn small-btn">Open Task</button>
                        </a>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>
</section>