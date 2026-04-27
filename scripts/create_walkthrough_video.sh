#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUT_DIR="$ROOT_DIR/storage/walkthrough-video"
NARRATION="$ROOT_DIR/walkthrough/narration.txt"

mkdir -p "$OUT_DIR/.cache"
export XDG_CACHE_HOME="$OUT_DIR/.cache"

slide() {
    local number="$1"
    local eyebrow="$2"
    local title="$3"
    local body="$4"
    local footer="$5"
    local file
    file=$(printf "%s/slide-%02d.png" "$OUT_DIR" "$number")

    magick -size 1920x1080 xc:"#f8fafc" \
        -fill "#eef2ff" -draw "roundrectangle 140,88 510,156 28,28" \
        -fill "#4f46e5" -font Arial-Bold -pointsize 30 -annotate +180+132 "AI Sales Page Generator" \
        -fill "#4f46e5" -draw "roundrectangle 140,200 360,248 20,20" \
        -fill "#ffffff" -font Arial-Bold -pointsize 24 -annotate +178+234 "$eyebrow" \
        \( -background none -fill "#020617" -font Arial-Bold -pointsize 72 -size 1420x190 caption:"$title" \) -geometry +140+292 -composite \
        \( -background none -fill "#475569" -font Arial -pointsize 40 -interline-spacing 10 -size 1450x430 caption:"$body" \) -geometry +145+520 -composite \
        -fill "#cbd5e1" -draw "line 140,970 1780,970" \
        -fill "#64748b" -font Arial-Bold -pointsize 26 -annotate +140+1025 "$footer" \
        -fill "#94a3b8" -font Arial -pointsize 24 -gravity SouthEast -annotate +140+52 "Slide $number / 9" \
        "$file"
}

slide 1 "INTRO" \
    "Technical Walkthrough" \
    "A Laravel app that transforms raw product or service information into a polished, structured sales landing page using AI." \
    "3-5 minute project overview"

slide 2 "PROBLEM" \
    "From raw offer details to a complete sales page" \
    $'- Users provide product information once\n- The app generates headline, benefits, features, pricing, proof, and CTA\n- The output is structured JSON, not raw AI text' \
    "Goal: reliable sales page generation"

slide 3 "STACK" \
    "Laravel fullstack architecture" \
    $'- Laravel 11 and Breeze-style authentication\n- Blade templates with Tailwind CSS\n- Vite asset pipeline\n- SQLite locally, MySQL-ready configuration\n- OpenAI API integration through a dedicated service' \
    "Simple architecture, assessment-ready"

slide 4 "AUTH" \
    "Protected user workflow" \
    $'- Register, login, and logout flows\n- Auth middleware protects generator, library, preview, edit, delete, and export\n- Sales pages belong to users\n- Policy authorization prevents cross-user access' \
    "Security scope: authenticated owner only"

slide 5 "INPUT" \
    "Product input and validation" \
    $'- Product name, description, audience, price, features, and selling points\n- Template options: modern, elegant, bold\n- Tone options: professional, friendly, persuasive\n- Clear validation messages and loading state' \
    "Clean form, responsive layout"

slide 6 "AI SERVICE" \
    "Structured AI generation" \
    $'- Controller validates input and calls AiSalesPageService\n- Service builds the prompt and requests JSON\n- JSON is parsed and normalized safely\n- Expected fields include headline, subheadline, benefits, features, pricing, proof, and CTA' \
    "No raw AI text is rendered directly"

slide 7 "DATABASE" \
    "Saved pages and persistence" \
    $'- Input fields are saved with generated_content JSON\n- Saved pages can be searched by product or headline\n- Users can preview, edit, regenerate, export, or delete\n- Each query is scoped to the logged-in user' \
    "Reusable generated content"

slide 8 "PREVIEW + EXPORT" \
    "Preview and standalone HTML export" \
    $'- Live preview renders a complete landing page\n- Template choice changes the visual style\n- Export downloads a standalone HTML file\n- Export uses Tailwind CDN for portability' \
    "Demo-ready output"

slide 9 "FALLBACK" \
    "Reliable fallback behavior" \
    $'- If the API key is missing, the app still works\n- API errors or invalid JSON trigger mock generation\n- Mock content is built from the user input\n- This keeps local demos and assessments stable' \
    "Laravel CRUD + AI integration + resilient UX"

say -v Samantha -r 170 -o "$OUT_DIR/narration.aiff" -f "$NARRATION"

ffmpeg -y \
    -loop 1 -t 18 -i "$OUT_DIR/slide-01.png" \
    -loop 1 -t 26 -i "$OUT_DIR/slide-02.png" \
    -loop 1 -t 28 -i "$OUT_DIR/slide-03.png" \
    -loop 1 -t 25 -i "$OUT_DIR/slide-04.png" \
    -loop 1 -t 26 -i "$OUT_DIR/slide-05.png" \
    -loop 1 -t 32 -i "$OUT_DIR/slide-06.png" \
    -loop 1 -t 27 -i "$OUT_DIR/slide-07.png" \
    -loop 1 -t 26 -i "$OUT_DIR/slide-08.png" \
    -loop 1 -t 32 -i "$OUT_DIR/slide-09.png" \
    -i "$OUT_DIR/narration.aiff" \
    -filter_complex "[0:v][1:v][2:v][3:v][4:v][5:v][6:v][7:v][8:v]concat=n=9:v=1:a=0,format=yuv420p[v]" \
    -map "[v]" -map 9:a \
    -c:v libx264 -r 30 -pix_fmt yuv420p \
    -c:a aac -b:a 160k \
    -shortest "$OUT_DIR/ai-sales-page-generator-walkthrough.mp4"

echo "$OUT_DIR/ai-sales-page-generator-walkthrough.mp4"
