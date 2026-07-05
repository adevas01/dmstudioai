// DM Studio AI - Modern interactive animations

console.log("DM Studio AI animations loaded.");

// Button interactions
const startButton = document.querySelector(".primary-btn");
const demoButton = document.querySelector(".secondary-btn");

if (startButton) {
    startButton.addEventListener("click", function () {
        alert("Welcome to DM Studio AI! The learning area will be added soon.");
    });
}

if (demoButton) {
    demoButton.addEventListener("click", function () {
        alert("Demo coming soon. This will show how the AI learning assistant works.");
    });
}

// Typing effect for AI Assistant
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

// Scroll reveal animation
const revealElements = document.querySelectorAll(
    ".feature-card, .learning-card, .page-card, .dashboard-card, .testimonial, .about-section"
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

// Animated dashboard progress bar
const progressFill = document.querySelector(".progress-fill");

if (progressFill) {
    progressFill.style.width = "0%";

    setTimeout(function () {
        progressFill.style.width = "75%";
    }, 500);
}

// Futuristic mouse glow effect
const glow = document.createElement("div");
glow.classList.add("mouse-glow");
document.body.appendChild(glow);

document.addEventListener("mousemove", function (event) {
    glow.style.left = event.clientX + "px";
    glow.style.top = event.clientY + "px";
});

// Add visible animation classes to hero elements
const robot = document.querySelector(".robot");
const floatingCards = document.querySelectorAll(".floating-card");

if (robot) {
    robot.classList.add("animated-robot");
}

floatingCards.forEach(function (card) {
    card.classList.add("animated-floating-card");
});

// Active navigation highlight
const currentRoute = new URLSearchParams(window.location.search).get("route");
const navLinks = document.querySelectorAll("nav a");

navLinks.forEach(function (link) {
    const linkRoute = new URL(link.href).searchParams.get("route");

    if (currentRoute && currentRoute === linkRoute) {
        link.classList.add("active-link");
    }

    if (!currentRoute && link.href.includes("route=home")) {
        link.classList.add("active-link");
    }
});

// Teacher dashboard student dropdown preview
const studentSelect = document.getElementById("studentSelect");
const studentPreview = document.getElementById("studentPreview");

if (studentSelect && studentPreview) {
    studentSelect.addEventListener("change", function () {
        const selectedOption = studentSelect.options[studentSelect.selectedIndex];

        if (!studentSelect.value) {
            studentPreview.classList.add("hidden-preview");
            return;
        }

        document.getElementById("previewName").textContent = selectedOption.dataset.name;
        document.getElementById("previewEmail").textContent = selectedOption.dataset.email;
        document.getElementById("previewStatus").textContent = selectedOption.dataset.status;
        document.getElementById("previewDate").textContent = selectedOption.dataset.date;

        document.getElementById("profileLink").href =
            "index.php?route=student-profile&id=" + studentSelect.value + "&nav=dmstudioai";

        studentPreview.classList.remove("hidden-preview");
    });
}