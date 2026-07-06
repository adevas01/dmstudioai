<?php
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: index.php?route=login&nav=" . $navToken);
    exit;
}
?>

<section class="page-section">
    <div class="page-card task-create-card">

        <h1>Create Activity</h1>
        <p class="page-intro">
            Create a simple SEND-friendly Digital Media task for your students.
        </p>

        <form action="actions/save-task.php" method="POST" class="task-form">

            <label for="title">Task Title</label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                placeholder="Example: Create a Digital Poster" 
                required
            >

            <label for="task_type">Task Type</label>
            <select id="task_type" name="task_type" required>
                <option value="">Choose task type</option>
                <option value="poster">Poster / Image Editing</option>
                <option value="video">Video Editing</option>
                <option value="presentation">Presentation</option>
                <option value="animation">Animation</option>
                <option value="webpage">Webpage / Coding</option>
                <option value="reflection">Reflection / Written Task</option>
            </select>

            <label for="description">Short Description</label>
            <textarea 
                id="description" 
                name="description" 
                rows="4" 
                placeholder="Explain the task in simple language."
                required
            ></textarea>

            <label for="instructions">Step-by-Step Instructions</label>
            <textarea 
                id="instructions" 
                name="instructions" 
                rows="6" 
                placeholder="1. Open Canva&#10;2. Choose a template&#10;3. Add a title&#10;4. Add one image"
                required
            ></textarea>

            <label for="success_criteria">Success Criteria</label>
            <textarea 
                id="success_criteria" 
                name="success_criteria" 
                rows="5" 
                placeholder="- I added a clear title&#10;- I used one image&#10;- I checked my spelling"
                required
            ></textarea>

            <label for="recommended_tool">Recommended Tool</label>
            <select id="recommended_tool" name="recommended_tool">
                <option value="Canva">Canva</option>
                <option value="CapCut">CapCut</option>
                <option value="PowerPoint">PowerPoint</option>
                <option value="Word">Microsoft Word</option>
                <option value="Photopea">Photopea</option>
                <option value="Wick Editor">Wick Editor</option>
                <option value="Tinkercad">Tinkercad</option>
                <option value="VS Code">VS Code</option>
                <option value="Other">Other</option>
            </select>

            <label for="support_level">Support Level</label>
            <select id="support_level" name="support_level">
                <option value="low">Low support</option>
                <option value="medium" selected>Medium support</option>
                <option value="high">High support</option>
            </select>

            <label for="deadline">Deadline</label>
            <input type="date" id="deadline" name="deadline">

            <button type="submit" class="top-btn">Save Task</button>

        </form>

    </div>
</section>