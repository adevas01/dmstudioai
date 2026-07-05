<section class="page-hero">
    <h1>Login</h1>
    <p>Access your DM Studio AI dashboard.</p>
</section>

<section class="about-section">
    <form action="actions/login-process.php" method="POST" class="auth-form">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="primary-btn">Login</button>
    </form>
</section>