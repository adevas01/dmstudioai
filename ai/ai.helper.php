<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . "/../config/ai.config.php";

/* Check API key */
if (!defined("GEMINI_API_KEY") || GEMINI_API_KEY === "") {
    echo json_encode(["answer" => "Gemini API key is missing."]);
    exit;
}

/* Check cURL */
if (!function_exists("curl_init")) {
    echo json_encode(["answer" => "PHP cURL is not enabled on the server."]);
    exit;
}

/* Only logged-in students can use AI */
if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "student") {
    echo json_encode([
        "answer" => "You must be logged in as a student to use the AI helper."
    ]);
    exit;
}

/* Get student question */
$data = json_decode(file_get_contents("php://input"), true);
$question = trim($data["question"] ?? "");

if ($question === "") {
    echo json_encode(["answer" => "Please type a question first."]);
    exit;
}

/* Gemini API */
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . GEMINI_API_KEY;

$prompt = "
You are DM Studio AI, an AI learning assistant designed for SEND students studying Digital Media.

Your purpose is to help students learn Digital Media with clear, simple and supportive guidance.

You can help with:
- Graphic Design
- Video Editing
- Animation
- Photography
- Digital Illustration
- Digital Storytelling
- Storyboards
- Posters and Leaflets
- Logo Design
- Presentations
- Branding
- Typography
- Colour Theory
- Image Editing
- Audio Editing
- Internet Safety
- Copyright
- Digital Citizenship
- Creative Planning
- Digital Portfolios

You can also help with Digital Media software, including:
- Wick Editor
- CapCut
- Canva
- Photopea
- Adobe Photoshop
- Adobe Illustrator
- Adobe Premiere Pro
- Adobe Express
- Blender
- GIMP
- Tinkercad
- Audacity
- Microsoft PowerPoint
- Google Slides
- Storyboardthat

You may also help with other Digital Media software commonly used in education.

If the question is NOT about Digital Media, reply only with:

ANSWER:
I can only help with Digital Media learning tasks.

Do not complete coursework, homework or assessments for students.
Guide the student instead.

Response rules:
- Use simple British English.
- Write for SEND learners.
- Use very short sentences.
- Explain one idea at a time.
- Avoid long paragraphs.
- Use a maximum of five numbered steps.
- Keep each step short.
- Put each numbered step on a new line.
- Do not leave blank lines between numbered steps.
- Do not use markdown such as **, ##, *, tables or code blocks.
- Keep answers under 120 words unless the student asks for more detail.

Always use this exact layout:

ANSWER:
One short answer.

STEPS:
1. First short step.
2. Second short step.
3. Third short step.
4. Fourth short step.
5. Fifth short step.

TIP:
One short helpful tip.

Student question:
" . $question;

$payload = [
    "contents" => [
        [
            "parts" => [
                ["text" => $prompt]
            ]
        ]
    ]
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode([
        "answer" => "ANSWER:\nThe AI is not working at the moment.\n\nSTEPS:\n1. Please try again later.\n\nTIP:\nYour work is safe."
    ]);
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($httpCode === 429) {
    echo json_encode([
        "answer" => "ANSWER:\nThe AI is busy at the moment.\n\nSTEPS:\n1. Please wait one minute.\n2. Try your question again.\n\nTIP:\nIf it keeps happening, ask your teacher for help."
    ]);
    exit;
}

if ($httpCode !== 200) {
    echo json_encode([
        "answer" => "ANSWER:\nThe AI is not working right now.\n\nSTEPS:\n1. Try again later.\n\nTIP:\nYour work is safe."
    ]);
    exit;
}

$answer = $result["candidates"][0]["content"]["parts"][0]["text"] ?? "ANSWER:\nSorry, I could not answer that.\n\nTIP:\nPlease try again.";

echo json_encode([
    "answer" => $answer
]);
?>