<?php
// Load the database connection.
// This gives this page access to the $pdo database object.
require_once "config/database.php";

// Load the authentication helper functions.
// This gives this page access to functions such as requireRoles().
require_once "includes/auth.php";

// Only teachers, managers, and the owner can access this dashboard.
// Students and visitors are not allowed to view this page.
requireRoles(["teacher", "manager", "owner"]);

// Prepare a database query to get all registered students.
// We only show users with the student role.
// We also ignore soft-deleted users.
$stmt = $pdo->prepare("
    SELECT id, name, email, status, created_at
    FROM dm_users
    WHERE role = 'student'
    AND deleted_at IS NULL
    ORDER BY name ASC
");

// Run the database query.
$stmt->execute();

// Store all students from the database in the $students array.
$students = $stmt->fetchAll();

// Count the number of registered students.
$totalStudents = count($students);

// Count approved students.
$approvedStudents = 0;

// Count pending students.
$pendingStudents = 0;

// Count blocked students.
$blockedStudents = 0;

// Loop through the students and count their status.
foreach ($students as $student) {
    if ($student["status"] === "approved") {
        $approvedStudents++;
    }

    if ($student["status"] === "pending") {
        $pendingStudents++;
    }

    if ($student["status"] === "blocked") {
        $blockedStudents++;
    }
}
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

        <!-- Example group information -->
        <p><strong>Group:</strong> SEND Level 1 Digital Media</p>

        <!-- Number of students found in the database -->
        <p><strong>Registered students:</strong> <?php echo $totalStudents; ?></p>

        <!-- Example current unit -->
        <p><strong>Current unit:</strong> Video Editing</p>

        <!-- Link to the user management page -->
        <a href="index.php?route=users&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">Manage Students</button>
        </a>
    </div>

    <!-- Student account summary card -->
    <div class="dashboard-card">
        <h2>Student Accounts</h2>

        <!-- Summary of student account statuses -->
        <p><strong>Approved:</strong> <?php echo $approvedStudents; ?></p>
        <p><strong>Pending:</strong> <?php echo $pendingStudents; ?></p>
        <p><strong>Blocked:</strong> <?php echo $blockedStudents; ?></p>
    </div>

    <!-- Student progress selector card -->
    <div class="dashboard-card large-card">
        <h2>Student Progress</h2>

        <!-- Short instruction for the teacher -->
        <p>Select a registered student and open their learning profile.</p>

        <!-- Show this message if no students are registered -->
        <?php if (empty($students)): ?>

            <p>No students registered yet.</p>

        <?php else: ?>

            <!-- Student profile selector panel -->
            <div class="student-progress-panel">

                <!-- Left side: dropdown and profile button -->
                <div class="student-select-area">

                    <!-- Label for accessibility -->
                    <label for="studentProfileSelect">Choose student</label>

                    <!-- Dropdown list of registered students -->
                    <select id="studentProfileSelect" class="student-select">

                        <!-- Default dropdown option -->
                        <option value="">Select a student...</option>

                        <!-- Loop through each student and create one option for each -->
                        <?php foreach ($students as $student): ?>

                            <!-- The value contains the student profile link -->
                            <option value="index.php?route=student-profile&id=<?php echo (int) $student["id"]; ?>&nav=<?php echo $navToken; ?>">
                                <?php echo htmlspecialchars($student["name"]); ?> — <?php echo htmlspecialchars(ucfirst($student["status"])); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <!-- This button uses JavaScript to open the selected student profile -->
                    <button type="button" class="primary-btn small-btn" onclick="openStudentProfile()">
                        View Student Profile
                    </button>
                </div>

                <!-- Right side: explanation of the student profile -->
                <div class="student-progress-info">
                    <h3>Student profile includes</h3>

                    <!-- Simple list explaining what the teacher can see -->
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

        <!-- Example support alerts for the prototype -->
        <!-- Later these alerts can come from the database -->
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