# NMG Forge 2 Hackathon Submission

## Project Name
PulseDesk

## Team
Shivansh Jaiswal

## Repository
https://github.com/Shivansh1146/forge2_shivansh

## Live Demo
https://forge2-shivansh.vercel.app

## Problem Solved
PulseDesk is a multi-tenant helpdesk and ticketing SaaS platform that enables organizations to manage support requests efficiently while maintaining tenant isolation and SLA tracking.

## Tech Stack
- Laravel 11
- React 19 + Vite
- Tailwind CSS
- PostgreSQL (Production) / MySQL (CI)
- Docker (Render Blueprint)

## Deployment
- Frontend: Vercel
- Backend: Render

---

## ✅ Submission Checklist & Evidence Map

- [x] **Agent Configs:** Committed in `agents/hermes/hermes-config.yaml` and `agents/openclaw/openclaw.json`.
- [x] **Redacted Secrets:** All keys in agent configs are redacted to `${EASTROUTER_API_KEY}`.
- [x] **EastRouter Models:** Used `deepseek/deepseek-v4-pro` (Hermes) and `z-ai/glm-5.1` (OpenClaw) exclusively.
- [x] **Agent Logs:** The raw interaction loop is logged in `agent-log.md`.
- [x] **Sprint Evidence:** Sprints 01, 02, and 03 are documented in `sprints/sprint-01.md`, `sprint-02.md`, and `sprint-03.md`.
- [x] **Slack Evidence:** 6 screenshots mapping to the 5 required channels are safely stored in `slack-export/screenshots/`.
- [x] **App/CI Evidence:** 18 screenshots of the live app, CI pipeline, and agent gateway are stored in `evidence/screenshots/`.
- [x] **Architecture Docs:** The data model and architecture flow are fully detailed in `README.md` and `ARCHITECTURE.md`.
- [x] **CI/CD Pipeline:** Fully functional and running on GitHub Actions (`.github/workflows/ci.yml`).

See `README.md` for complete run steps and project documentation.
