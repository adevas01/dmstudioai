<section class="page-hero">
    <h1>Login</h1>
    <p>Access your DM Studio AI dashboard.</p>
</section>

<section class="about-section auth-box">

    <?php if (isset($_GET["message"]) && $_GET["message"] === "registered"): ?>
        <div class="success-message">
            Account created successfully. Please wait for approval before logging in.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET["message"]) && $_GET["message"] === "loggedout"): ?>
        <div class="success-message">
            You have logged out successfully.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET["error"])): ?>
        <?php if ($_GET["error"] === "empty"): ?>
            <div class="error-message">
                Please enter your email and password.
            </div>
        <?php elseif ($_GET["error"] === "invalid"): ?>
            <div class="error-message">
                Invalid email or password. Please try again.
            </div>
        <?php elseif ($_GET["error"] === "pending"): ?>
            <div class="warning-message">
                Your account is waiting for approval. A teacher, manager, or owner needs to approve your account before you can access your dashboard.
            </div>
        <?php elseif ($_GET["error"] === "blocked"): ?>
            <div class="error-message">
                Your account has been blocked. Please contact your teacher or manager.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form action="actions/login-process.php" method="POST" class="auth-form">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="primary-btn">Login</button>
    </form>

    <p class="auth-link">
        Do not have an account?
        <a href="index.php?route=register&nav=<?php echo $navToken; ?>">Create one here</a>
    </p>
</section>