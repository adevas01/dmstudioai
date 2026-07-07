<?php 
// Start the PHP session so the website can remember logged-in users.
session_start();

// DM Studio AI - Front Controller.
// This file controls which page is shown based on the route in the URL.

// Navigation token used in prototype URLs.
// This is not a security feature.
$navToken = "dmstudioai";

// Project root folder.
$rootPath = __DIR__;

// Allowed routes only.
$routes = [
    // Public pages.
    "home" => "pages/home.php",
    "courses" => "pages/courses.php",
    "tools" => "pages/tools.php",
    "about" => "pages/about.php",
    "intro-lesson" => "pages/intro-lesson.php",

    // Privacy and security page.
    "privacy-security" => "security/privacy-security.php",

    // Dashboard pages.
    "student" => "pages/student-dashboard.php",
    "teacher" => "pages/teacher-dashboard.php",
    "manager" => "pages/manager-dashboard.php",
    "owner" => "pages/owner-dashboard.php",

    // Teacher task pages.
    "create-task" => "pages/create-task.php",
    "view-tasks" => "pages/view-tasks.php",
    "assign-task" => "pages/assign-task.php",

    // Student task pages.
    "my-tasks" => "pages/my-tasks.php",
    "student-task" => "pages/student-task.php",

    // User management pages.
    "users" => "pages/user-management.php",
    "student-profile" => "pages/student-profile.php",
    "review-submission" => "pages/review-submission.php",

    // Student quick action pages.
    "lessons" => "pages/lessons.php",
    "submit-work" => "pages/submit-work.php",
    "feedback" => "pages/feedback.php",

    // Authentication pages.
    "login" => "pages/login.php",
    "register" => "pages/register.php"
];

// Page titles.
$pageTitles = [
    // Public page titles.
    "home" => "DM Studio AI",
    "courses" => "Courses | DM Studio AI",
    "tools" => "Tools | DM Studio AI",
    "about" => "About | DM Studio AI",
    "intro-lesson" => "pages/intro-lesson.php",

    // Privacy and security page title.
    "privacy-security" => "Privacy & Security | DM Studio AI",

    // Dashboard page titles.
    "student" => "Student Dashboard | DM Studio AI",
    "teacher" => "Teacher Dashboard | DM Studio AI",
    "manager" => "Manager Dashboard | DM Studio AI",
    "owner" => "Owner Dashboard | DM Studio AI",

    // Teacher task page titles.
    "create-task" => "Create Task | DM Studio AI",
    "view-tasks" => "View Tasks | DM Studio AI",
    "assign-task" => "Assign Task | DM Studio AI",

    // Student task page titles.
    "my-tasks" => "My Tasks | DM Studio AI",
    "student-task" => "Student Task | DM Studio AI",

    // User management page titles.
    "users" => "User Management | DM Studio AI",
    "student-profile" => "Student Profile | DM Studio AI",
    "review-submission" => "Review Submission | DM Studio AI",

    // Student quick action page titles.
    "lessons" => "Lessons | DM Studio AI",
    "submit-work" => "Submit Work | DM Studio AI",
    "feedback" => "Feedback | DM Studio AI",

    // Authentication page titles.
    "login" => "Login | DM Studio AI",
    "register" => "Register | DM Studio AI"
];

// Get route from URL, or use home as default.
$route = $_GET["route"] ?? "home";

// Clean route value.
$route = strtolower(trim($route));

// Track errors.
$isInvalidRoute = false;
$isMissingFile = false;

// If route does not exist, show page not found message.
if (!array_key_exists($route, $routes)) {
    $isInvalidRoute = true;
    $pageTitle = "Page Not Found | DM Studio AI";
    $pageFile = null;
} else {
    // Set page title and page file.
    $pageTitle = $pageTitles[$route] ?? "DM Studio AI";
    $pageFile = $rootPath . "/" . $routes[$route];

    // Safety check for missing page files.
    if (!file_exists($pageFile)) {
        $isMissingFile = true;
        $pageTitle = "Page File Missing | DM Studio AI";
        $pageFile = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <?php include $rootPath . "/includes/head.php"; ?>
</head>

<body>

<?php include $rootPath . "/includes/header.php"; ?>

<main>
    <?php if ($isInvalidRoute): ?>
        <section class="page-hero">
            <h1>Page Not Found</h1>
            <p>The page you requested does not exist. Please check the link or return to the homepage.</p>
            <a href="index.php?route=home&nav=<?php echo htmlspecialchars($navToken); ?>" class="hero-btn">
                Back to Home
            </a>
        </section>

    <?php elseif ($isMissingFile): ?>
        <section class="page-hero">
            <h1>Page File Missing</h1>
            <p>The route exists, but the page file could not be found.</p>
            <p>Please check that this file exists:</p>
            <p><strong>security/privacy-security.php</strong></p>
        </section>

    <?php elseif ($pageFile !== null): ?>
        <?php include $pageFile; ?>
    <?php endif; ?>
</main>

<?php include $rootPath . "/includes/footer.php"; ?>

</body>
</html>