<header>
    <div class="logo">
        <span>DM</span> Studio AI
    </div>

    <nav>
        <a href="index.php?route=home&nav=<?php echo $navToken; ?>">Home</a>
        <a href="index.php?route=home&nav=<?php echo $navToken; ?>#features">Features</a>
        <a href="index.php?route=courses&nav=<?php echo $navToken; ?>">Courses</a>
        <a href="index.php?route=tools&nav=<?php echo $navToken; ?>">Tools</a>
        <a href="index.php?route=about&nav=<?php echo $navToken; ?>">About</a>

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

            <a href="actions/logout.php">Logout</a>

        <?php else: ?>

            <a href="index.php?route=login&nav=<?php echo $navToken; ?>">Login</a>
            <a href="index.php?route=register&nav=<?php echo $navToken; ?>">Register</a>

        <?php endif; ?>
    </nav>

    <?php if (isset($_SESSION["user_id"])): ?>
        <a href="index.php?route=<?php echo htmlspecialchars($_SESSION["role"]); ?>&nav=<?php echo $navToken; ?>">
            <button class="top-btn">Dashboard</button>
        </a>
    <?php else: ?>
        <a href="index.php?route=register&nav=<?php echo $navToken; ?>">
            <button class="top-btn">Get Started</button>
        </a>
    <?php endif; ?>
</header>