<section class="page-hero">
    <h1>Create Account</h1>
    <p>Register as a student or teacher. Your account will need approval before full access.</p>
</section>

<section class="about-section">
    <form action="actions/register-process.php" method="POST" class="auth-form">
        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Role</label>
        <select name="role" required>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select>

        <button type="submit" class="primary-btn">Register</button>
    </form>
</section>