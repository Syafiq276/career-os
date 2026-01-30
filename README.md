# CareerOS – Job Hunter Dashboard

CareerOS is a gamified job application tracker and portfolio system built with Laravel. It helps users manage job applications, showcase projects as RPG-style quests, and analyze application progress with actionable insights.

## Key Features

### Portfolio (RPG HUD)
- Public portfolio page with neon RPG UI and skill radar chart.
- Projects sync from GitHub repositories.
- Industry theme selector (IT, Finance, Engineering, Design, Healthcare) with layout variations.
- Contact section with user info and message form.

### Job Application Tracker
- Full CRUD for applications with statuses: applied, screening, interview, offer, rejected, ghosted.
- Analytics dashboard: funnel counts, response rates, offer rates, ghosted rate, and average response time.
- Response time tracking (days between applied and first response).

### Skills & Growth
- Skill tree management with manual entry.
- Auto-generate skills from GitHub project tech stacks.

### Resume Scanner Tool
- ATS-style resume keyword matcher against job descriptions.
- Score visualization with missing keyword suggestions.

## Tech Stack
- Laravel 11
- MySQL (local) / PostgreSQL (Render)
- Tailwind CSS (CDN fallback)
- Chart.js
- Docker + Docker Compose

## Local Setup (Docker)

1. Copy env:
	- Copy .env.docker to .env
2. Build and run:
	- docker compose up -d --build
3. Generate key:
	- docker compose run --rm app php artisan key:generate
4. Migrate & seed:
	- docker compose exec app php artisan migrate --force
	- docker compose exec app php artisan db:seed --force
5. Open:
	- http://localhost:8080

## OAuth (GitHub/Google)

Set these environment variables in production (Render):
- GITHUB_CLIENT_ID / GITHUB_CLIENT_SECRET
- GOOGLE_CLIENT_ID / GOOGLE_CLIENT_SECRET

Callback URLs:
- https://<your-domain>/auth/github/callback
- https://<your-domain>/auth/google/callback

## Notes

- Portfolio themes and layout variations are driven by the industry selector.
- Response time is calculated once a status leaves “applied” (or when interview date is set).
- Resume Scanner expects PDF uploads only.

## Screenshots (safe to share)

Add screenshots without exposing personal data (emails, tokens, real names). Suggested files:
- docs/screenshots/portfolio-it.png
- docs/screenshots/applications-analytics.png
- docs/screenshots/resume-scanner.png

Example (replace with your images):
- Portfolio (IT theme)
- Application analytics
- Resume Scanner

## Architecture Overview

- Laravel MVC for core app logic.
- Blade templates for UI.
- MySQL/PostgreSQL persistence.
- Optional GitHub OAuth and sync.

## Security & Privacy Notes

- Do NOT commit .env or credentials.
- Use environment variables for OAuth secrets and database credentials.
- Scrub screenshots (emails, IDs, company names) before sharing.

## License

MIT
