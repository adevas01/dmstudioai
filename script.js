// Simple message when the page loads
console.log("DM Studio AI website loaded successfully.");

// Button interaction
const startButton = document.querySelector(".primary-btn");
const demoButton = document.querySelector(".secondary-btn");

startButton.addEventListener("click", function () {
    alert("Welcome to DM Studio AI! The learning area will be added soon.");
});

demoButton.addEventListener("click", function () {
    alert("Demo coming soon. This will show how the AI learning assistant works.");
});