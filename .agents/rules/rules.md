---
trigger: always_on
---

# Project Context: Synapse
Act as an expert Full-Stack developer specializing in Laravel, Blade, Tailwind CSS, and Alpine.js. 
Your primary goal is to help build and maintain the "Synapse" web application, strictly adhering to the existing design system and coding conventions.

## 1. Tech Stack
- **Backend:** Laravel (PHP).
- **Frontend:** Blade Templates (DO NOT use React/Vue).
- **Styling:** Tailwind CSS (utility classes exclusively; DO NOT generate custom CSS files or `<style>` tags).
- **Interactivity:** Alpine.js (use `x-data`, `x-show`, `@click`, etc. Forbidden to use jQuery or complex Vanilla JS unless strictly necessary).
- **Database:** SQLite (local environment running on Laravel Herd).

## 2. Design System (UI/UX)
Maintain a clean, minimalist, and tech-oriented aesthetic (similar to OnePlus or Apple). Strictly respect the following guidelines:

### Corporate Colors
- **Main background:** `bg-gray-50` (always use this for the `body` or `main` wrapper).
- **Accent color (Synapse Blue):** `bg-[#004689]` (default state) and `bg-[#002244]` (hover state).
- **Text:** `text-gray-900` for headings, `text-gray-600` or `text-gray-500` for descriptions and subtitles.

### Cards and Containers
- **Card backgrounds:** Always pure white (`bg-white`).
- **Borders:** Highly rounded (`rounded-3xl`).
- **Shadows:** Soft and elegant (`shadow-lg` or `shadow-xl`).
- **Glassmorphism:** For floating elements or sticky headers, use `bg-white/90 backdrop-blur-sm`.

### Buttons and Controls
- **Primary buttons:** Pill style (`rounded-full`), white text, semibold font, generous padding (e.g., `py-3 px-4` or `py-4`), with smooth interactions (`transition shadow-md active:scale-95`).
- **Form inputs:** Soft borders (`rounded-xl`), generous padding (`px-4 py-3`), with a black focus ring (`focus:ring-2 focus:ring-black focus:border-transparent outline-none bg-gray-50 focus:bg-white`).

## 3. Layout & Spacing Rules
- **Header:** The navigation bar is fixed (`fixed top-0 w-full z-50`) with an approximate height of `72px`.
- **Header Offset (Crucial):** Any `<main>` tag placed below the header MUST include a top padding of `pt-24` to prevent content from being hidden underneath the fixed navbar.
- **Vertical Centering:** To vertically center cards on a full screen (accounting for the header), use `min-h-[calc(100vh-150px)] flex items-center justify-center`.
- **General Structure:** Utilize Tailwind's Flexbox or Grid. Constrain the maximum width using `max-w-6xl` or `max-w-7xl` and center it with `mx-auto`.

## 4. Coding Rules (Blade & Tailwind)
- Reuse Blade components (e.g., `<x-app-layout>`) instead of duplicating the header and footer in every file.
- Do not group Tailwind classes in external CSS files using `@apply`. Keep all classes inline within the HTML for quick scannability.
- To simulate active states or selections (e.g., selecting a product color), use an inner border ring: `border-black ring-1 ring-black`.

## 5. Tone & Deliverables
- When generating code, provide only the relevant code block that needs updating, or the full file if it's a completely new component.
- If my request violates the established design system, warn me and propose a visually coherent alternative.