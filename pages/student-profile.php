<?php
require_once "config/database.php";
require_once "includes/auth.php";

requireRoles(["teacher", "manager", "owner"]);

$studentId = (int) ($_GET["id"] ?? 0);

if ($studentId <= 0) {
    echo '
    <section class="page-hero">
        <h1>Student Not Found</h1>
        <p>No student was selected.</p>
    </section>';
    return;
}

$stmt = $pdo->prepare("
    SELECT id, name, email, status, created_at
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
        <p>This student does not exist or has been deleted.</p>
    </section>';
    return;
}
?>

<section class="dashboard-hero">
    <h1><?php echo htmlspecialchars($student["name"]); ?></h1>
    <p>Student learning profile and progress overview.</p>
</section>

<section class="dashboard-layout">

    <div class="dashboard-card large-card">
        <h2>Student Details</h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($student["email"]); ?></p>
        <p><strong>Status:</strong>
            <span class="status-badge status-<?php echo htmlspecialchars($student["status"]); ?>">
                <?php echo htmlspecialchars(ucfirst($student["status"])); ?>
            </span>
        </p>
        <p><strong>Registered:</strong> <?php echo date("d M Y", strtotime($student["created_at"])); ?></p>

        <a href="index.php?route=teacher&nav=<?php echo $navToken; ?>">
            <button class="secondary-btn small-btn">Back to Teacher Dashboard</button>
        </a>
    </div>

    <div class="dashboard-card large-card">
        <h2>Learning Progress</h2>

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

    <div class="dashboard-card">
        <h2>Teacher Notes</h2>
        <p>Notes and feedback will be added here later.</p>
    </div>

    <div class="dashboard-card">
        <h2>Submitted Work</h2>
        <p>Student work submissions will appear here in the next development stage.</p>
    </div>

</section>