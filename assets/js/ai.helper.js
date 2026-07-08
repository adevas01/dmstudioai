async function askAI() {
    const questionBox = document.getElementById("aiQuestion");
    const answerBox = document.getElementById("aiAnswer");
    const button = document.getElementById("aiButton");

    const question = questionBox.value.trim();

    if (question === "") {
        answerBox.innerHTML = "<div class='ai-message-error'>Please type a question first.</div>";
        return;
    }

    answerBox.innerHTML = "<div class='ai-loading'>🤖 Thinking...</div>";
    button.disabled = true;
    button.innerHTML = "Thinking...";

    try {
        const response = await fetch("/ai/ai.helper.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ question: question })
        });

        const data = await response.json();
        answerBox.innerHTML = formatAIResponse(data.answer);

    } catch (error) {
        answerBox.innerHTML = "<div class='ai-message-error'>Sorry, something went wrong.</div>";
    }

    button.disabled = false;
    button.innerHTML = "Ask AI";
}

function formatAIResponse(text) {
    let cleanText = text
        .replace(/<br\s*\/?>/gi, "\n")
        .replace(/&lt;br\s*\/?&gt;/gi, "\n")
        .replace(/&amp;/g, "&")
        .replace(/&#039;/g, "'")
        .replace(/&quot;/g, '"')
        .trim();

    const answer = extractSection(cleanText, "ANSWER", "STEPS");
    const steps = extractSection(cleanText, "STEPS", "TIP");
    const tip = extractSection(cleanText, "TIP", null);

    return `
        <div class="ai-section ai-answer-section">
            <h3><span class="ai-icon blue"></span>💡 Answer</h3>
            <p>${escapeHTML(answer || cleanText || "I can help with that.")}</p>
        </div>

        ${steps ? `
            <div class="ai-section ai-steps-section">
                <h3><span class="ai-icon green"></span>📋 Steps</h3>
                ${formatSteps(steps)}
            </div>
        ` : ""}

        ${tip ? `
            <div class="ai-section ai-tip-section">
                <h3><span class="ai-icon yellow"></span>⭐ Tip</h3>
                <p>${escapeHTML(tip)}</p>
            </div>
        ` : ""}
    `;
}

function extractSection(text, startLabel, endLabel) {
    const startRegex = new RegExp("^\\s*" + startLabel + "\\s*:?", "im");
    const startMatch = text.match(startRegex);

    if (!startMatch) {
        return "";
    }

    const contentStart = startMatch.index + startMatch[0].length;
    let contentEnd = text.length;

    if (endLabel) {
        const endRegex = new RegExp("^\\s*" + endLabel + "\\s*:?", "im");
        const remainingText = text.slice(contentStart);
        const endMatch = remainingText.match(endRegex);

        if (endMatch) {
            contentEnd = contentStart + endMatch.index;
        }
    }

    return text.slice(contentStart, contentEnd).trim();
}

function formatSteps(stepsText) {
    const steps = stepsText
        .split(/\n+/)
        .map(step => step.trim())
        .filter(step => step !== "")
        .map(step => step.replace(/^\d+\.\s*/, ""));

    if (steps.length === 0) {
        return `<p>${escapeHTML(stepsText)}</p>`;
    }

    return `
        <ol class="ai-steps-list">
            ${steps.map(step => `<li>${escapeHTML(step)}</li>`).join("")}
        </ol>
    `;
}

function escapeHTML(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener("DOMContentLoaded", function () {
    const questionBox = document.getElementById("aiQuestion");

    if (questionBox) {
        questionBox.addEventListener("keydown", function (e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                askAI();
            }
        });
    }
});