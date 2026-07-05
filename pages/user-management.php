<?php
require_once "config/database.php";
require_once "includes/auth.php";

requireRoles(["teacher", "manager", "owner"]);

$currentRole = $_SESSION["role"];

if ($currentRole === "owner") {
    $stmt = $pdo->prepare("
        SELECT id, name, email, role, status, created_at
        FROM dm_users
        WHERE role IN ('student', 'teacher', 'manager')
        AND deleted_at IS NULL
        ORDER BY created_at DESC
    ");
} elseif ($currentRole === "manager") {
    $stmt = $pdo->prepare("
        SELECT id, name, email, role, status, created_at
        FROM dm_users
        WHERE role IN ('student', 'teacher')
        AND deleted_at IS NULL
        ORDER BY created_at DESC
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT id, name, email, role, status, created_at
        FROM dm_users
        WHERE role = 'student'
        AND deleted_at IS NULL
        ORDER BY created_at DESC
    ");
}

$stmt->execute();
$users = $stmt->fetchAll();
?>

<section class="dashboard-hero">
    <h1>User Management</h1>
    <p>Approve, block, review, or safely delete user accounts based on your role.</p>
</section>

<section class="user-admin-page">

    <?php if (isset($_GET["message"])): ?>
        <?php if ($_GET["message"] === "updated"): ?>
            <div class="success-message">User status updated successfully.</div>
        <?php elseif ($_GET["message"] === "deleted"): ?>
            <div class="success-message">User deleted successfully.</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="user-admin-header">
        <div>
            <h2>Accounts</h2>
            <p>
                Logged in as <strong><?php echo htmlspecialchars(ucfirst($_SESSION["role"])); ?></strong>.
                You can only manage users allowed by your permission level.
            </p>
        </div>

        <a href="index.php?route=<?php echo htmlspecialchars($_SESSION["role"]); ?>&nav=<?php echo $navToken; ?>" class="back-link">
            Back to Dashboard
        </a>
    </div>

    <?php if (empty($users)): ?>

        <div class="empty-state">
            <h3>No users found</h3>
            <p>There are no accounts available for your role to manage yet.</p>
        </div>

    <?php else: ?>

        <div class="user-card-list">
            <?php foreach ($users as $user): ?>
                <div class="user-card">
                    <div class="user-main-info">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($user["name"], 0, 1)); ?>
                        </div>

                        <div>
                            <h3><?php echo htmlspecialchars($user["name"]); ?></h3>
                            <p><?php echo htmlspecialchars($user["email"]); ?></p>
                        </div>
                    </div>

                    <div class="user-meta">
                        <span class="role-badge role-<?php echo htmlspecialchars($user["role"]); ?>">
                            <?php echo htmlspecialchars(ucfirst($user["role"])); ?>
                        </span>

                        <span class="status-badge status-<?php echo htmlspecialchars($user["status"]); ?>">
                            <?php echo htmlspecialchars(ucfirst($user["status"])); ?>
                        </span>

                        <span class="date-text">
                            Created: <?php echo date("d M Y", strtotime($user["created_at"])); ?>
                        </span>
                    </div>

                    <div class="user-actions">
                        <?php if (canManageUser($currentRole, $user["role"])): ?>

                            <form action="actions/update-user-status.php" method="POST" class="status-form">
                                <input type="hidden" name="user_id" value="<?php echo (int) $user["id"]; ?>">

                                <select name="status">
                                    <option value="pending" <?php if ($user["status"] === "pending") echo "selected"; ?>>Pending</option>
                                    <option value="approved" <?php if ($user["status"] === "approved") echo "selected"; ?>>Approved</option>
                                    <option value="blocked" <?php if ($user["status"] === "blocked") echo "selected"; ?>>Blocked</option>
                                </select>

                                <button type="submit" class="primary-btn small-btn">Update</button>
                            </form>

                            <form action="actions/delete-user.php" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this user? This will hide the account from the system.');">
                                <input type="hidden" name="user_id" value="<?php echo (int) $user["id"]; ?>">
                                <button type="submit" class="danger-btn">Delete</button>
                            </form>

                        <?php else: ?>

                            <span class="muted-text">No permission</span>

                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</section>