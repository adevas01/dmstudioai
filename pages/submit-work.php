<?php
// Load authentication helper functions.
require_once "includes/auth.php";

// Only students can access this submit work page.
requireRoles(["student"]);
?>

<section class="dashboard-hero">
    <h1>Submit Work</h1>
    <p>Upload or describe your Digital Media project for your teacher to review.</p>
</section>

<section class="student-portal-layout">

    <div class="student-portal-card large-student-card">
        <h2>Current Assignment</h2>
        <p><strong>Assignment:</strong> Short Video Draft</p>
        <p>Create and submit a short video using cuts, text, music, and simple effects.</p>
        <p><strong>Due date:</strong> Friday</p>
    </div>

    <div class="student-portal-card large-student-card">
        <h2>Submit Your Work</h2>

        <form class="auth-form" action="#" method="POST" enctype="multipart/form-data">
            <label>Project title</label>
            <input type="text" name="project_title" placeholder="Example: My College Video">

            <label>Short description</label>
            <textarea name="project_description" placeholder="Write a short description of your work."></textarea>

            <label>Upload file</label>
            <input type="file" name="project_file">

            <button type="submit" class="primary-btn">Submit Work</button>
        </form>
    </div>

    <div class="student-portal-card">
        <h2>Submission Checklist</h2>
        <ul class="student-task-list">
            <li>My video has a clear beginning</li>
            <li>I added music or sound</li>
            <li>I checked spelling</li>
            <li>I exported my file correctly</li>
        </ul>
    </div>

</section>