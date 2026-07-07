<header class="dm-header">  
    <div class="dm-header-inner">
        <!-- Logo -->
        <div class="logo">
            <span>DM</span> Studio AI
        </div>

        <!-- Hamburger Menu Toggle -->
        <button class="hamburger" id="hamburger" aria-label="Toggle navigation menu" aria-expanded="false">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <!-- Navigation -->
        <nav class="nav-menu" id="navMenu" role="navigation" aria-label="Main navigation">
            <a href="index.php?route=home&nav=<?php echo $navToken; ?>">Home</a>
            <!--<a href="index.php?route=home&nav=<?php echo $navToken; ?>#features">Features</a>-->
            <a href="index.php?route=courses&nav=<?php echo $navToken; ?>">Courses</a>
            <a href="index.php?route=tools&nav=<?php echo $navToken; ?>">Tools</a>
            <a href="index.php?route=about&nav=<?php echo $navToken; ?>">About</a>
            <a href="index.php?route=privacy-security&nav=<?php echo $navToken; ?>">Privacy</a>

            <?php if (isset($_SESSION["user_id"])): ?>
                <?php if ($_SESSION["role"] === "student"): ?>
                    <a href="index.php?route=student&nav=<?php echo $navToken; ?>">Dashboard</a>
                <?php endif; ?>

                <?php if ($_SESSION["role"] === "teacher"): ?>
                    <a href="index.php?route=teacher&nav=<?php echo $navToken; ?>">Dashboard</a>
                <?php endif; ?>

                <?php if ($_SESSION["role"] === "manager"): ?>
                    <a href="index.php?route=manager&nav=<?php echo $navToken; ?>">Dashboard</a>
                <?php endif; ?>

                <?php if ($_SESSION["role"] === "owner"): ?>
                    <a href="index.php?route=owner&nav=<?php echo $navToken; ?>">Dashboard</a>
                <?php endif; ?>

                <a href="actions/logout.php" class="nav-logout">Logout</a>
            <?php else: ?>
                <a href="index.php?route=login&nav=<?php echo $navToken; ?>">Login</a>
                <a href="index.php?route=register&nav=<?php echo $navToken; ?>">Register</a>
            <?php endif; ?>

            <!-- CTA Button inside mobile menu -->
            <div class="nav-cta-mobile">
                <?php if (isset($_SESSION["user_id"])): ?>
                    <a href="index.php?route=<?php echo htmlspecialchars($_SESSION["role"]); ?>&nav=<?php echo $navToken; ?>">
                        <button class="top-btn">Dashboard</button>
                    </a>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Desktop CTA Button -->
        <div class="nav-cta-desktop">
            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="index.php?route=<?php echo htmlspecialchars($_SESSION["role"]); ?>&nav=<?php echo $navToken; ?>">
                    <button class="top-btn">Dashboard</button>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>