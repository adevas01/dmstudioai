<?php
require_once "config/database.php";
require_once "includes/auth.php";

requireRoles(["teacher", "manager", "owner"]);

$currentRole = $_SESSION["role"];

// Decide which users this role can see
if ($currentRole === "owner") {
    $stmt = $pdo->prepare("
        SELECT id, name, email, role, status, created_at
        FROM dm_users
        WHERE role IN ('student', 'teacher', 'manager')
        AND deleted_at IS NULL
        ORDER BY created_at DESC
    ");
    $stmt->execute();
} elseif ($currentRole === "manager") {
    $stmt = $pdo->prepare("
        SELECT id, name, email, role, status, created_at
        FROM dm_users
        WHERE role IN ('student', 'teacher')
        AND deleted_at IS NULL
        ORDER BY created_at DESC
    ");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("
        SELECT id, name, email, role, status, created_at
        FROM dm_users
        WHERE role = 'student'
        AND deleted_at IS NULL
        ORDER BY created_at DESC
    ");
    $stmt->execute();
}

$users = $stmt->fetchAll();
?>

<section class="dashboard-hero">
    <h1>User Management</h1>
    <p>Approve, block, or review user accounts based on your role.</p>
</section>

<section class="about-section">
    <?php if (isset($_GET["message"]) && $_GET["message"] === "updated"): ?>
        <p class="success-message">User status updated successfully.</p>
    <?php endif; ?>

    <?php if (empty($users)): ?>
        <p>No users found.</p>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user["name"]); ?></td>
                            <td><?php echo htmlspecialchars($user["email"]); ?></td>
                            <td><?php echo htmlspecialchars($user["role"]); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo htmlspecialchars($user["status"]); ?>">
                                    <?php echo htmlspecialchars($user["status"]); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($user["created_at"]); ?></td>
                            <td>
                                <?php if (canManageUser($currentRole, $user["role"])): ?>
                                    <form action="actions/update-user-status.php" method="POST" class="inline-form">
                                        <input type="hidden" name="user_id" value="<?php echo (int) $user["id"]; ?>">

                                        <select name="status">
                                            <option value="pending" <?php if ($user["status"] === "pending") echo "selected"; ?>>Pending</option>
                                            <option value="approved" <?php if ($user["status"] === "approved") echo "selected"; ?>>Approved</option>
                                            <option value="blocked" <?php if ($user["status"] === "blocked") echo "selected"; ?>>Blocked</option>
                                        </select>

                                        <button type="submit" class="primary-btn small-btn">Update</button>
                                    </form>
                                <?php else: ?>
                                    <span>No permission</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>