<section class="dashboard-hero">
    <h1>Manager Dashboard</h1>
    <p>Manage students, teachers, classes, and learning content.</p>
</section>

<section class="dashboard-layout">
    <div class="dashboard-card large-card">
        <h2>Manager Overview</h2>
        <p><strong>Role:</strong> Manager</p>
        <p>
            Managers can support the running of the platform by approving students,
            approving teachers, checking progress, and reviewing learning content.
        </p>

        <a href="index.php?route=users&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">Manage Users</button>
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Manage Students</h2>
        <p>Approve, block, or review student accounts.</p>

        <a href="index.php?route=users&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">View Students</button>
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Manage Teachers</h2>
        <p>Approve teacher accounts and support class organisation.</p>

        <a href="index.php?route=users&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">View Teachers</button>
        </a>
    </div>

    <div class="dashboard-card">
        <h2>Manager Rules</h2>
        <ul>
            <li>Managers can approve students.</li>
            <li>Managers can approve teachers.</li>
            <li>Managers cannot manage the owner.</li>
            <li>Managers cannot delete or control the owner account.</li>
        </ul>
    </div>

    <div class="dashboard-card large-card">
        <h2>Manage Content</h2>
        <p>
            Review lessons, digital media activities, AI tools, learning resources,
            and student support materials.
        </p>

        <button class="primary-btn small-btn">View Content</button>
    </div>
</section>