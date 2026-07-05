<?php
// Load authentication helper functions.
require_once "includes/auth.php";

// Only students can access this lessons page.
requireRoles(["student"]);
?>

<section class="dashboard-hero">
    <h1>My Lessons</h1>
    <p>Continue your Digital Media learning step by step.</p>
</section>

<section class="student-portal-layout">

    <div class="student-portal-card large-student-card">
        <h2>Video Editing Basics</h2>
        <p>Learn how to cut clips, add transitions, use music, and export a short video.</p>

        <div class="mini-progress-bar">
            <div class="mini-progress-fill" style="width: 75%;"></div>
        </div>

        <p><strong>Progress:</strong> 75% complete</p>
        <button class="primary-btn small-btn">Open Lesson</button>
    </div>

    <div class="student-portal-card">
        <h2>Lesson 1</h2>
        <p><strong>Topic:</strong> Introduction to video editing</p>
        <p>Status: Completed</p>
    </div>

    <div class="student-portal-card">
        <h2>Lesson 2</h2>
        <p><strong>Topic:</strong> Cutting and trimming clips</p>
        <p>Status: Completed</p>
    </div>

    <div class="student-portal-card">
        <h2>Lesson 3</h2>
        <p><strong>Topic:</strong> Adding music and sound</p>
        <p>Status: In progress</p>
    </div>

    <div class="student-portal-card">
        <h2>Lesson 4</h2>
        <p><strong>Topic:</strong> Exporting your final video</p>
        <p>Status: Not started</p>
    </div>

</section>