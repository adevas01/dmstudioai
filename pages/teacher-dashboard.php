<?php
// Load the database connection.
// This gives this page access to the $pdo database object.
require_once "config/database.php";

// Load the authentication helper functions.
// This gives access to functions like requireRoles().
require_once "includes/auth.php";

// Only allow teachers, managers, and the owner to view this page.
// Students and visitors cannot access the teacher dashboard.
requireRoles(["teacher", "manager", "owner"]);

// Prepare a database query to get all registered students.
// We only select students who have not been soft-deleted.
$stmt = $pdo->prepare("
    SELECT id, name, email, status, created_at
    FROM dm_users
    WHERE role = 'student'
    AND deleted_at IS NULL
    ORDER BY name ASC
");

// Run the database query.
$stmt->execute();

// Store all student records in the $students array.
$students = $stmt->fetchAll();
?>

<!-- Main teacher dashboard banner -->
<section class="dashboard-hero">
    <h1>Teacher Dashboard</h1>
    <p>Monitor student progress, support learners, and manage digital media activities.</p>
</section>

<!-- Main dashboard layout -->
<section class="dashboard-layout">

    <!-- Class overview card -->
    <div class="dashboard-card large-card">
        <h2>Class Overview</h2>

        <!-- Example class/group information -->
        <p><strong>Group:</strong> SEND Level 1 Digital Media</p>

        <!-- Shows the number of registered students from the database -->
        <p><strong>Registered students:</strong> <?php echo count($students); ?></p>

        <!-- Example current learning unit -->
        <p><strong>Current unit:</strong> Video Editing</p>

        <!-- Link to the user management page where teachers can manage students -->
        <a href="index.php?route=users&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">Manage Students</button>
        </a>
    </div>

    <!-- Student progress card -->
    <div class="dashboard-card large-card">
        <h2>Student Progress</h2>

        <!-- Instruction for the teacher -->
        <p>Select a registered student and open their learning profile.</p>

        <!-- If there are no students in the database, show a message -->
        <?php if (empty($students)): ?>
            <p>No students registered yet.</p>

        <!-- If there are students, show the dropdown selector -->
        <?php else: ?>

            <!-- Student selection panel -->
            <div class="student-progress-panel">

                <!-- Left side: dropdown and button -->
                <div class="student-select-area">

                    <!-- Label for accessibility -->
                    <label for="studentProfileSelect">Choose student</label>

                    <!-- Dropdown list of students -->
                    <select id="studentProfileSelect" class="student-select">

                        <!-- Default option before the teacher chooses a student -->
                        <option value="">Select a student...</option>

                        <!-- Loop through each student from the database -->
                        <?php foreach ($students as $student): ?>

                            <!-- Each option stores the link to that student's profile page -->
                            <option value="index.php?route=student-profile&id=<?php echo (int) $student["id"]; ?>&nav=<?php echo $navToken; ?>">
                                <?php echo htmlspecialchars($student["name"]); ?> — <?php echo htmlspecialchars(ucfirst($student["status"])); ?>
                            </option>

                        <?php endforeach; ?>
                    </select>

                    <!-- Button that opens the selected student's profile using JavaScript -->
                    <button type="button" class="primary-btn small-btn" onclick="openStudentProfile()">
                        View Student Profile
                    </button>
                </div>

                <!-- Right side: explanation of what the teacher can access -->
                <div class="student-progress-info">
                    <h3>What the teacher can see</h3>

                    <ul>
                        <li>Student account status</li>
                        <li>Registration date</li>
                        <li>Digital media progress</li>
                        <li>Teacher notes and feedback area</li>
                        <li>Submitted work area</li>
                    </ul>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <!-- Support alerts card -->
    <div class="dashboard-card">
        <h2>Support Alerts</h2>

        <!-- These are example alerts for now -->
        <!-- Later, these can come from the database -->
        <ul>
            <li>2 students need help with planning</li>
            <li>1 student has not submitted work</li>
            <li>3 students completed the checklist</li>
        </ul>
    </div>

    <!-- Create activity card -->
    <div class="dashboard-card">
        <h2>Create Activity</h2>

        <!-- Short explanation for the teacher -->
        <p>Create a SEND-friendly task with simple steps and success criteria.</p>

        <!-- This button can later link to a real create-activity page -->
        <button class="primary-btn small-btn">Create Task</button>
    </div>

</section>