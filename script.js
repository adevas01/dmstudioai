// DM Studio AI - Modern interactive animations.
// This file controls small animations, navigation highlighting, and student profile selection.

console.log("DM Studio AI scripts loaded.");


// --------------------------------------------------
// Typing effect for AI Assistant card
// --------------------------------------------------

// Find the AI assistant text inside the floating card on the homepage.
const aiText = document.querySelector(".card-one p");

// Only run the typing effect if the AI assistant text exists on the page.
if (aiText) {
    // This is the message that will be typed automatically.
    const message = "Hi! How can I help you learn today?";

    // This keeps track of the current character position.
    let index = 0;

    // Clear the text before starting the typing animation.
    aiText.textContent = "";

    // Create the typing function.
    function typeMessage() {
        // Continue while there are still characters to type.
        if (index < message.length) {
            // Add one character at a time.
            aiText.textContent += message.charAt(index);

            // Move to the next character.
            index++;

            // Wait briefly before typing the next character.
            setTimeout(typeMessage, 45);
        }
    }

    // Start typing after a short delay.
    setTimeout(typeMessage, 600);
}


// --------------------------------------------------
// Scroll reveal animation
// --------------------------------------------------

// Select elements that should fade in when they appear on screen.
const revealElements = document.querySelectorAll(
    ".feature-card, .learning-card, .page-card, .dashboard-card, .testimonial, .about-section, .user-card, .user-admin-header, .student-progress-panel"
);

// Only create the observer if there are elements to animate.
if (revealElements.length > 0) {
    // Create an observer that watches when elements enter the screen.
    const revealObserver = new IntersectionObserver(
        function (entries) {
            // Check each observed element.
            entries.forEach(function (entry) {
                // If the element is visible, add the show class.
                if (entry.isIntersecting) {
                    entry.target.classList.add("show");
                }
            });
        },
        {
            // Start animation when 15% of the element is visible.
            threshold: 0.15
        }
    );

    // Add reveal class to each element and start observing it.
    revealElements.forEach(function (element) {
        element.classList.add("reveal");
        revealObserver.observe(element);
    });
}


// --------------------------------------------------
// Animated dashboard progress bar
// --------------------------------------------------

// Find the main progress bar if it exists.
const progressFill = document.querySelector(".progress-fill");

// Animate the main progress bar.
if (progressFill) {
    // Start from zero.
    progressFill.style.width = "0%";

    // Animate to 75%.
    setTimeout(function () {
        progressFill.style.width = "75%";
    }, 500);
}


// --------------------------------------------------
// Futuristic mouse glow effect
// --------------------------------------------------

// Create a glowing circle that follows the mouse.
const glow = document.createElement("div");

// Add the CSS class for styling the glow.
glow.classList.add("mouse-glow");

// Add the glow element to the page.
document.body.appendChild(glow);

// Move the glow when the mouse moves.
document.addEventListener("mousemove", function (event) {
    glow.style.left = event.clientX + "px";
    glow.style.top = event.clientY + "px";
});


// --------------------------------------------------
// Hero robot animations
// --------------------------------------------------

// Find the robot on the homepage.
const robot = document.querySelector(".robot");

// Find the floating cards around the robot.
const floatingCards = document.querySelectorAll(".floating-card");

// Add animation class to the robot if it exists.
if (robot) {
    robot.classList.add("animated-robot");
}

// Add animation class to each floating card.
floatingCards.forEach(function (card) {
    card.classList.add("animated-floating-card");
});


// --------------------------------------------------
// Active navigation highlight
// --------------------------------------------------

// Get the current route from the URL.
// Example: index.php?route=teacher
const currentRoute = new URLSearchParams(window.location.search).get("route");

// Find all navigation links.
const navLinks = document.querySelectorAll("nav a");

// Loop through each navigation link.
navLinks.forEach(function (link) {
    // Read the route from the link.
    const linkUrl = new URL(link.href);
    const linkRoute = linkUrl.searchParams.get("route");

    // If the current page route matches the link route, highlight the link.
    if (currentRoute && currentRoute === linkRoute) {
        link.classList.add("active-link");
    }

    // If there is no route in the URL, highlight Home.
    if (!currentRoute && link.href.includes("route=home")) {
        link.classList.add("active-link");
    }

    // If the current route is a student profile, keep Dashboard highlighted.
    if (currentRoute === "student-profile" && linkRoute === "teacher") {
        link.classList.add("active-link");
    }

    // If the current route is user management, keep Dashboard highlighted.
    if (currentRoute === "users" && linkRoute === "owner") {
        link.classList.add("active-link");
    }
});


// --------------------------------------------------
// Teacher dashboard: open selected student profile
// --------------------------------------------------

// This function is used by the Teacher Dashboard button.
// It opens the selected student profile page.
function openStudentProfile() {
    // Find the student dropdown.
    const select = document.getElementById("studentProfileSelect");

    // If the dropdown does not exist, stop the function.
    if (!select) {
        return;
    }

    // If no student is selected, show a small message.
    if (select.value === "") {
        alert("Please choose a student first.");
        return;
    }

    // Redirect the teacher to the selected student's profile page.
    window.location.href = select.value;
}


// --------------------------------------------------
// Improve student dropdown behaviour
// --------------------------------------------------

// Find the student profile dropdown on the teacher dashboard.
const studentProfileSelect = document.getElementById("studentProfileSelect");

// If the dropdown exists, allow Enter key navigation.
if (studentProfileSelect) {
    // Listen for keyboard actions.
    studentProfileSelect.addEventListener("keydown", function (event) {
        // If the teacher presses Enter, open the selected profile.
        if (event.key === "Enter") {
            openStudentProfile();
        }
    });
}


// --------------------------------------------------
// Confirm delete buttons
// --------------------------------------------------

// Find all delete forms.
const deleteForms = document.querySelectorAll("form[data-confirm-delete='true']");

// Add a confirmation message before deleting users.
deleteForms.forEach(function (form) {
    form.addEventListener("submit", function (event) {
        const confirmed = confirm(
            "Are you sure you want to delete this user? This will hide the account from the system."
        );

        if (!confirmed) {
            event.preventDefault();
        }
    });
});