<?php
// Start the PHP session so the website can remember logged-in users.
session_start();

// DM Studio AI - Front Controller.
// This file controls which page is shown based on the route in the URL.

// Example URL:
// index.php?route=about&nav=dmstudioai

// This navigation token is used to keep your prototype URLs consistent.
// It is not a security feature.
$navToken = "dmstudioai";

// This array stores all allowed website routes.
// The key is the route name used in the URL.
// The value is the PHP file that will be loaded.
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

    // Authentication pages.
    "login" => "pages/login.php",
    "register" => "pages/register.php"
];

// This array stores the browser tab title for each route.
// It also helps with accessibility and page identification.
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

    // Authentication page titles.
    "login" => "Login | DM Studio AI",
    "register" => "Register | DM Studio AI"
];

// Get the route from the URL.
// If there is no route in the URL, use the homepage.
$route = $_GET["route"] ?? "home";

// Remove extra spaces from the route.
// Convert the route to lowercase to avoid route errors.
$route = strtolower(trim($route));

// Create a variable to check if the requested route is invalid.
// It starts as false because we assume the route is correct.
$isInvalidRoute = false;

// Check if the requested route exists in the allowed routes array.
// If it does not exist, send the user to the homepage.
if (!array_key_exists($route, $routes)) {
    // Change the route to home.
    $route = "home";

    // Mark the route as invalid so we can show a warning message.
    $isInvalidRoute = true;
}

// Get the correct page title for the selected route.
$pageTitle = $pageTitles[$route];

// Get the correct PHP page file for the selected route.
$pageFile = $routes[$route];

// Extra safety check.
// If the selected page file does not exist, do not include it.
if (!file_exists($pageFile)) {
    // Change the page title to Page Not Found.
    $pageTitle = "Page Not Found | DM Studio AI";

    // Set the page file to null so no missing file is included.
    $pageFile = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Show the correct page title in the browser tab. -->
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Load the shared head file. -->
    <!-- This should include meta tags, CSS links, and any shared head settings. -->
    <?php include "includes/head.php"; ?>
</head>

<body>

<!-- Load the shared header and navigation menu. -->
<?php include "includes/header.php"; ?>

<!-- Main page content starts here. -->
<main>

    <!-- If the user entered an invalid route, show a friendly warning. -->
    <?php if ($isInvalidRoute): ?>
        <section class="page-hero">
            <h1>Page Not Found</h1>
            <p>
                The page you requested does not exist. You have been redirected to the DM Studio AI homepage.
            </p>
        </section>
    <?php endif; ?>

    <?php
    // If the page file exists, include it here.
    if ($pageFile !== null) {
        // Load the selected page content.
        include $pageFile;
    } else {
        // If the page file is missing, show a clear error message.
        echo '
        <section class="page-hero">
            <h1>Page File Missing</h1>
            <p>The requested page file could not be found. Please check the pages folder.</p>
        </section>';
    }
    ?>

</main>
<!-- Main page content ends here. -->

<!-- Load the shared footer. -->
<?php include "includes/footer.php"; ?>

</body>
</html>