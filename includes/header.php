<header class="dm-header">
    <div class="dm-header-inner">

        <!-- Logo -->
        <div class="logo">
            <span>DM</span> Studio AI
        </div>

        <!-- Hamburger Menu Toggle -->
        <button
            class="hamburger"
            id="hamburger"
            type="button"
            aria-label="Toggle navigation menu"
            aria-expanded="false"
            aria-controls="navMenu">

            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <!-- Navigation -->
        <nav
            class="nav-menu"
            id="navMenu"
            aria-label="Main navigation">

            <a href="index.php?route=home&nav=<?php echo htmlspecialchars($navToken); ?>">
                Home
            </a>

            <a href="index.php?route=courses&nav=<?php echo htmlspecialchars($navToken); ?>">
                Courses
            </a>

            <a href="index.php?route=tools&nav=<?php echo htmlspecialchars($navToken); ?>">
                Tools
            </a>

            <a href="index.php?route=about&nav=<?php echo htmlspecialchars($navToken); ?>">
                About
            </a>

            <a href="index.php?route=privacy-security&nav=<?php echo htmlspecialchars($navToken); ?>">
                Privacy
            </a>

            <?php if (isset($_SESSION["user_id"])): ?>

                <a href="actions/logout.php" class="nav-logout">
                    Logout
                </a>

            <?php else: ?>

                <a href="index.php?route=login&nav=<?php echo htmlspecialchars($navToken); ?>">
                    Login
                </a>

                <a href="index.php?route=register&nav=<?php echo htmlspecialchars($navToken); ?>">
                    Register
                </a>

            <?php endif; ?>

            <!-- Dashboard button inside mobile menu -->
            <?php if (isset($_SESSION["user_id"], $_SESSION["role"])): ?>
                <div class="nav-dashboard-mobile">
                    <a
                        class="nav-dashboard-button"
                        href="index.php?route=<?php echo htmlspecialchars($_SESSION["role"]); ?>&nav=<?php echo htmlspecialchars($navToken); ?>">
                        Dashboard
                    </a>
                </div>
            <?php endif; ?>

        </nav>

        <!-- Dashboard button on desktop -->
        <?php if (isset($_SESSION["user_id"], $_SESSION["role"])): ?>
            <div class="nav-dashboard-desktop">
                <a
                    class="nav-dashboard-button"
                    href="index.php?route=<?php echo htmlspecialchars($_SESSION["role"]); ?>&nav=<?php echo htmlspecialchars($navToken); ?>">
                    Dashboard
                </a>
            </div>
        <?php endif; ?>

    </div>
</header>