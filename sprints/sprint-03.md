# Sprint 03 — CI/CD Pipeline & Production Deployment
**Goal:** Automate testing with GitHub Actions and deploy to Render (Backend) and Vercel (Frontend).
**Status:** Complete

## Issues
1. Write GitHub Actions workflow (`ci.yml`) to provision MySQL and run PHPUnit tests on every PR.
2. Create `render.yaml` blueprint and `Dockerfile` (PHP 8.4) to host the Laravel API on Render.
3. Fix React Router SPA fallback issues on Vercel by creating `vercel.json` rewrites.
4. Finalize README documentation with live URLs, architecture diagrams, and a comprehensive screenshot gallery.

## Outcome
- CI pipeline successfully passes on GitHub Actions.
- Backend API live on Render with auto-migrations executed.
- Frontend live on Vercel with deep-linking fixed (no more 404s on page refresh).
- Project 100% submission ready.
