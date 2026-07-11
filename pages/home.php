<section class="hero hero-pro">
    <div class="hero-bg-shape shape-one"></div>
    <div class="hero-bg-shape shape-two"></div>

    <div class="hero-text">
        <p class="hero-kicker">Create • Learn • Build • Share</p>

        <h1>
            Learn <span>Digital Media</span><br>
            with AI Support
        </h1>

        <p>
            A creative and accessible learning space where SEND students can build
            real skills in video, design, animation, storytelling, and AI-assisted projects.
        </p>

        <div class="hero-buttons">
            <?php
                $allowedDashboardRoles = ["student", "teacher", "manager", "owner"];
                $dashboardRoute = null;

                if (
                    isset($_SESSION["user_id"]) &&
                    isset($_SESSION["role"]) &&
                    in_array($_SESSION["role"], $allowedDashboardRoles, true)
                ) {
                    $dashboardRoute = $_SESSION["role"];
                }
            ?>

            <?php if ($dashboardRoute !== null): ?>
                <a href="index.php?route=<?php echo htmlspecialchars($dashboardRoute); ?>&nav=<?php echo $navToken; ?>" class="primary-btn">
                    Start Creating
                </a>
            <?php else: ?>
                <a href="index.php?route=intro-lesson&nav=<?php echo $navToken; ?>" class="primary-btn">
                    Start Creating
                </a>
            <?php endif; ?>

            <a href="index.php?route=tools&nav=<?php echo $navToken; ?>" class="secondary-btn">
                Explore Tools
            </a>
        </div>

        <div class="small-tags">
            <span>✓ SEND-Friendly</span>
            <span>🎨 Creative Projects</span>
            <span>🤖 AI Support</span>
            <span>🛡 Safe Learning</span>
        </div>
    </div>

   
    
   
<div class="hero-image hero-studio">

    <div class="pixel-background-glow"></div>

    <div class="creative-orbit" aria-hidden="true">
        <span class="orbit-item orbit-video">🎬</span>
        <span class="orbit-item orbit-design">🎨</span>
        <span class="orbit-item orbit-mobile">📱</span>
        <span class="orbit-item orbit-audio">🎧</span>
    </div>

    <div class="pixel-robot-wrapper">
        <div class="pixel-ring pixel-ring-one"></div>
        <div class="pixel-ring pixel-ring-two"></div>

        <div class="pixel-image-frame">
            <img
                src="assets/images/pixel-robot.png"
                alt="Pixel, the friendly DM Studio AI learning assistant"
                class="pixel-robot-image"
            >

            <div class="pixel-image-shine"></div>
        </div>

        <div class="pixel-shadow"></div>
    </div>

    <div class="floating-card card-one">
        <div class="floating-card-icon">✨</div>

        <div>
            <strong>Ask Pixel</strong>
            <p>Need help with your creative project?</p>
        </div>
    </div>

    <div class="floating-card card-two">
        <div class="progress-card-heading">
            <strong>Project Progress</strong>
            <span>83%</span>
        </div>

        <div class="progress-track">
            <div class="progress-fill"></div>
        </div>

        <p>Great work — keep creating!</p>
    </div>

    <div class="pixel-status">
        <span class="pixel-status-dot"></span>
        Pixel is ready to help
    </div>
</div>





</section>

<section class="features" id="features">
    <div class="feature-card">
        <div class="icon">🎬</div>
        <h3>Video Editing</h3>
        <p>Create short videos, add effects, cut clips, and build confidence with tools like CapCut.</p>
    </div>

    <div class="feature-card">
        <div class="icon">🎨</div>
        <h3>Graphic Design</h3>
        <p>Design posters, thumbnails, logos, and social media graphics using creative apps.</p>
    </div>

    <div class="feature-card">
        <div class="icon">🤖</div>
        <h3>AI Study Assistant</h3>
        <p>Get step-by-step explanations, feedback, and simple guidance whenever students need support.</p>
    </div>

    <div class="feature-card">
        <div class="icon">📊</div>
        <h3>Progress Tracking</h3>
        <p>Students and teachers can track learning, goals, achievements, and completed activities.</p>
    </div>
</section>

<section class="learning">
    <div class="section-heading">
        <p class="section-kicker">Digital Media Pathways</p>
        <h2>Choose What You Want to Create</h2>
    </div>

    <div class="learning-grid">
        <div class="learning-card">
            <span class="learning-icon">🎥</span>
            <h3>Video Editing</h3>
            <p>Learn to cut, edit, add music, captions, and effects.</p>
            <a href="index.php?route=courses&nav=<?php echo $navToken; ?>">Explore →</a>
        </div>

        <div class="learning-card">
            <span class="learning-icon">🖼️</span>
            <h3>Graphic Design</h3>
            <p>Create posters, layouts, thumbnails, and visual designs.</p>
            <a href="index.php?route=courses&nav=<?php echo $navToken; ?>">Explore →</a>
        </div>

        <div class="learning-card">
            <span class="learning-icon">✨</span>
            <h3>Animation</h3>
            <p>Bring characters and ideas to life with movement.</p>
            <a href="index.php?route=courses&nav=<?php echo $navToken; ?>">Explore →</a>
        </div>

        <div class="learning-card">
            <span class="learning-icon">📖</span>
            <h3>Digital Storytelling</h3>
            <p>Tell stories using images, sound, video, and voice.</p>
            <a href="index.php?route=courses&nav=<?php echo $navToken; ?>">Explore →</a>
        </div>
    </div>
</section>

<section class="student-showcase">
    <div>
        <p class="section-kicker">Featured Project</p>
        <h2>Build Real Creative Work</h2>
        <p>
            Students can create videos, posters, animations, presentations, and digital stories
            while receiving clear, accessible support at every step.
        </p>
    </div>

    <div class="showcase-card">
        <span>⭐ Student Project</span>
        <h3>My First Digital Story</h3>
        <p>Created with images, voice, captions, and simple video editing.</p>
    </div>
</section>

<section class="testimonial">
    <div>
        <p>
            “DM Studio AI helps students build confidence, explore creativity,
            and achieve their learning goals.”
        </p>
        <strong>— Digital Media Teacher</strong>
    </div>

    <div>
        <p>
            “The step-by-step help makes learning easier and more fun.”
        </p>
        <strong>— SEND Learner</strong>
    </div>
</section>