// DM Studio AI - Modern interactive animations

console.log("DM Studio AI animations loaded.");

// --------------------------------------------------
// Button interactions
// --------------------------------------------------
// The old alert messages were removed.
// Buttons should now navigate normally or perform their real actions.
// This prevents dashboard buttons from showing unwanted pop-up messages.


// --------------------------------------------------
// Typing effect for AI Assistant card
// --------------------------------------------------
const aiText = document.querySelector(".card-one p");

if (aiText) {
    const message = "Hi! How can I help you learn today?";
    let index = 0;

    aiText.textContent = "";

    function typeMessage() {
        if (index < message.length) {
            aiText.textContent += message.charAt(index);
            index++;
            setTimeout(typeMessage, 45);
        }
    }

    setTimeout(typeMessage, 600);
}


// --------------------------------------------------
// Scroll reveal animation
// --------------------------------------------------
const revealElements = document.querySelectorAll(
    ".feature-card, .learning-card, .page-card, .dashboard-card, .testimonial, .about-section, .user-card, .user-admin-header"
);

if (revealElements.length > 0) {
    const revealObserver = new IntersectionObserver(
        function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("show");
                }
            });
        },
        {
            threshold: 0.15
        }
    );

    revealElements.forEach(function (element) {
        element.classList.add("reveal");
        revealObserver.observe(element);
    });
}


// --------------------------------------------------
// Animated dashboard progress bar
// --------------------------------------------------
const progressFill = document.querySelector(".progress-fill");

if (progressFill) {
    progressFill.style.width = "0%";

    setTimeout(function () {
        progressFill.style.width = "75%";
    }, 500);
}


// --------------------------------------------------
// Futuristic mouse glow effect
// --------------------------------------------------
const glow = document.createElement("div");
glow.classList.add("mouse-glow");
document.body.appendChild(glow);

document.addEventListener("mousemove", function (event) {
    glow.style.left = event.clientX + "px";
    glow.style.top = event.clientY + "px";
});


// --------------------------------------------------
// Hero robot animations
// --------------------------------------------------
const robot = document.querySelector(".robot");
const floatingCards = document.querySelectorAll(".floating-card");

if (robot) {
    robot.classList.add("animated-robot");
}

floatingCards.forEach(function (card) {
    card.classList.add("animated-floating-card");
});


// --------------------------------------------------
// Active navigation highlight
// --------------------------------------------------
const currentRoute = new URLSearchParams(window.location.search).get("route");
const navLinks = document.querySelectorAll("nav a");

navLinks.forEach(function (link) {
    const linkUrl = new URL(link.href);
    const linkRoute = linkUrl.searchParams.get("route");

    if (currentRoute && currentRoute === linkRoute) {
        link.classList.add("active-link");
    }

    if (!currentRoute && link.href.includes("route=home")) {
        link.classList.add("active-link");
    }
});


// --------------------------------------------------
// Teacher dashboard: open selected student profile
// --------------------------------------------------
function openStudentProfile() {
    const select = document.getElementById("studentProfileSelect");

    if (!select || select.value === "") {
        alert("Please choose a student first.");
        return;
    }

    window.location.href = select.value;
}


// --------------------------------------------------
// Optional older student preview system
// --------------------------------------------------
// This is kept for compatibility only.
// If the page does not use studentSelect/studentPreview, it will do nothing.

const studentSelect = document.getElementById("studentSelect");
const studentPreview = document.getElementById("studentPreview");

if (studentSelect && studentPreview) {
    studentSelect.addEventListener("change", function () {
        const selectedOption = studentSelect.options[studentSelect.selectedIndex];

        if (!studentSelect.value) {
            studentPreview.classList.add("hidden-preview");
            return;
        }

        const previewName = document.getElementById("previewName");
        const previewEmail = document.getElementById("previewEmail");
        const previewStatus = document.getElementById("previewStatus");
        const previewDate = document.getElementById("previewDate");
        const profileLink = document.getElementById("profileLink");

        if (previewName) {
            previewName.textContent = selectedOption.dataset.name;
        }

        if (previewEmail) {
            previewEmail.textContent = selectedOption.dataset.email;
        }

        if (previewStatus) {
            previewStatus.textContent = selectedOption.dataset.status;
        }

        if (previewDate) {
            previewDate.textContent = selectedOption.dataset.date;
        }

        if (profileLink) {
            profileLink.href =
                "index.php?route=student-profile&id=" + studentSelect.value + "&nav=dmstudioai";
        }

        studentPreview.classList.remove("hidden-preview");
    });
}