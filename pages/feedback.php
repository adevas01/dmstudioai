<?php
// Load authentication helper functions.
require_once "includes/auth.php";

// Only students can access this feedback page.
requireRoles(["student"]);
?>

<section class="dashboard-hero">
    <h1>Teacher Feedback</h1>
    <p>Review your teacher comments and improve your Digital Media work.</p>
</section>

<section class="student-portal-layout">

    <div class="student-portal-card large-student-card">
        <h2>Latest Feedback</h2>
        <p><strong>Teacher:</strong> Digital Media Teacher</p>
        <p><strong>Project:</strong> Short Video Draft</p>
        <p>
            Good progress this week. Your video has a clear structure.
            Remember to upload your storyboard and check spelling before submitting the final version.
        </p>
    </div>

    <div class="student-portal-card">
        <h2>What Went Well</h2>
        <ul class="student-task-list">
            <li>You used clear clips</li>
            <li>You followed the project checklist</li>
            <li>Your video idea is creative</li>
        </ul>
    </div>

    <div class="student-portal-card">
        <h2>Even Better If</h2>
        <ul class="student-task-list">
            <li>Add smoother transitions</li>
            <li>Check spelling in your titles</li>
            <li>Make the ending clearer</li>
        </ul>
    </div>

    <div class="student-portal-card">
        <h2>Next Step</h2>
        <p>Improve your video draft and submit the final version by Friday.</p>
        <a href="index.php?route=submit-work&nav=<?php echo $navToken; ?>">
            <button class="primary-btn small-btn">Submit Improved Work</button>
        </a>
    </div>

</section>