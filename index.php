<?php
// DM Studio AI - Front Controller
// All pages are loaded through index.php using the route parameter.
// Example: index.php?route=about&nav=dmstudioai

// Navigation token used in URLs.
// This is for prototype navigation structure, not security.
$navToken = "dmstudioai";

// Allowed routes only.
// This prevents users from loading random files through the URL.
$routes = [
    "home" => "pages/home.php",
    "courses" => "pages/courses.php",
    "tools" => "pages/tools.php",
    "about" => "pages/about.php",

    // Dashboard pages
    "student" => "pages/student-dashboard.php",
    "teacher" => "pages/teacher-dashboard.php",
    "manager" => "pages/manager-dashboard.php",
    "owner" => "pages/owner-dashboard.php",

    // Authentication pages
    "login" => "pages/login.php",
    "register" => "pages/register.php"
];

// Page titles for browser tab and accessibility.
$pageTitles = [
    "home" => "DM Studio AI",
    "courses" => "Courses | DM Studio AI",
    "tools" => "Tools | DM Studio AI",
    "about" => "About | DM Studio AI",

    // Dashboard page titles
    "student" => "Student Dashboard | DM Studio AI",
    "teacher" => "Teacher Dashboard | DM Studio AI",
    "manager" => "Manager Dashboard | DM Studio AI",
    "owner" => "Owner Dashboard | DM Studio AI",

    // Authentication page titles
    "login" => "Login | DM Studio AI",
    "register" => "Register | DM Studio AI"
];

// Get route from URL.
// If no route is provided, show the homepage.
$route = $_GET["route"] ?? "home";

// Clean the route value.
$route = strtolower(trim($route));

// Check if the route exists.
// If the route is not allowed, show home and mark it as invalid.
$isInvalidRoute = false;

if (!array_key_exists($route, $routes)) {
    $route = "home";
    $isInvalidRoute = true;
}

// Set the page title and page file.
$pageTitle = $pageTitles[$route];
$pageFile = $routes[$route];

// Extra safety: check if the page file exists.
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
            <p>
                The page you requested does not exist. You have been redirected to the DM Studio AI homepage.
            </p>
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