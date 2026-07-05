<?php
// Start the PHP session so the website can remember logged-in users.
session_start();

// DM Studio AI - Front Controller.
// This file controls which page is shown based on the route in the URL.

// Navigation token used in prototype URLs.
// This is not a security feature.
$navToken = "dmstudioai";

// Allowed routes only.
$routes = [
    // Public pages.
    "home" => "pages/home.php",
    "courses" => "pages/courses.php",
    "tools" => "pages/tools.php",
    "about" => "pages/about.php",

    // Dashboard pages.
    "student" => "pages/student-dashboard.php",
    "teacher" => "pages/teacher-dashboard.php",
    "manager" => "pages/manager-dashboard.php",
    "owner" => "pages/owner-dashboard.php",

    // User management pages.
    "users" => "pages/user-management.php",
    "student-profile" => "pages/student-profile.php",

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

    // Dashboard page titles.
    "student" => "Student Dashboard | DM Studio AI",
    "teacher" => "Teacher Dashboard | DM Studio AI",
    "manager" => "Manager Dashboard | DM Studio AI",
    "owner" => "Owner Dashboard | DM Studio AI",

    // User management page titles.
    "users" => "User Management | DM Studio AI",
    "student-profile" => "Student Profile | DM Studio AI",

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

// Track invalid routes.
$isInvalidRoute = false;

// If route does not exist, redirect content to home.
if (!array_key_exists($route, $routes)) {
    $route = "home";
    $isInvalidRoute = true;
}

// Set page title and page file.
$pageTitle = $pageTitles[$route];
$pageFile = $routes[$route];

// Safety check for missing page files.
if (!file_exists($pageFile)) {
    $pageTitle = "Page Not Found | DM Studio AI";
    $pageFile = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <?php include "includes/head.php"; ?>
</head>

<body>

<?php include "includes/header.php"; ?>

<main>
    <?php if ($isInvalidRoute): ?>
        <section class="page-hero">
            <h1>Page Not Found</h1>
            <p>The page you requested does not exist. You have been redirected to the DM Studio AI homepage.</p>
        </section>
    <?php endif; ?>

    <?php
    if ($pageFile !== null) {
        include $pageFile;
    } else {
        echo '
        <section class="page-hero">
            <h1>Page File Missing</h1>
            <p>The requested page file could not be found. Please check the pages folder.</p>
        </section>';
    }
    ?>
</main>

<?php include "includes/footer.php"; ?>

</body>
</html>