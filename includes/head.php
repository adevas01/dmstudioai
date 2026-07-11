<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>DM Studio AI</title>

<link rel="icon" type="image/png" href="images/favicon.png?v=4">
<link rel="shortcut icon" href="images/favicon.ico?v=4">
<link rel="apple-touch-icon" href="images/apple-touch-icon.png?v=4">

<!-- Main styles -->
<link rel="stylesheet" href="style.css?v=50">

<!-- Page-specific styles -->
<?php if (($route ?? 'home') === 'home'): ?>
    <link rel="stylesheet" href="assets/css/home-style.css?v=10">
<?php endif; ?>

<?php if (($route ?? '') === 'student'): ?>
    <link rel="stylesheet" href="assets/css/student-style.css?v=17">
<?php endif; ?>

<!-- Header must load LAST -->
<link rel="stylesheet" href="assets/css/header-style.css?v=8">