<section class="dashboard-hero">
    <h1>Owner Dashboard</h1>
    <p>Full system control for DM Studio AI.</p>
</section>

<section class="dashboard-layout">
    <div class="dashboard-card large-card">
        <h2>System Overview</h2>
        <p><strong>Role:</strong> Owner / Super Admin</p>
        <p>
            The owner has full control of the platform, including students, teachers,
            managers, content, account approvals, and system settings.
        </p>

        <a href="index.php?route=users&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">Manage Users</button>
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Manage Users</h2>
        <ul>
            <li>Approve students</li>
            <li>Approve teachers</li>
            <li>Approve or manage managers</li>
            <li>Block or delete users</li>
        </ul>

        <a href="index.php?route=users&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">Open User Management</button>
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Owner Rules</h2>
        <ul>
            <li>No one can delete the owner.</li>
            <li>The owner can delete users except himself.</li>
            <li>The owner can manage managers.</li>
            <li>The owner has full access to the system.</li>
        </ul>
    </div>

    <div class="dashboard-card">
        <h2>Account Approval</h2>
        <p>
            Review new student, teacher, and manager accounts before they can access
            their dashboards.
        </p>

        <a href="index.php?route=users&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">Approve Accounts</button>
        </a>
    </div>

    <div class="dashboard-card large-card">
        <h2>Content Control</h2>
        <p>
            Manage lessons, digital media activities, AI tools, dashboard content,
            and learning support materials.
        </p>

        <button class="primary-btn small-btn">Manage Content</button>
    </div>
</section>