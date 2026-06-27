# Sprint 02 — Multi-Tenancy, SLAs, & API Integration
**Goal:** Enforce strict data isolation, build the SLA engine, and connect the React frontend.
**Status:** Complete

## Issues
1. Implement global scopes and middleware in Laravel to isolate tickets by `organization_id`.
2. Build the SLA monitoring engine (calculate breaches based on priority thresholds).
3. Secure API endpoints using Laravel Sanctum and Role-Based Access Control (Admin vs Agent vs Customer).
4. Integrate the React frontend (React Query + Axios) to consume the secured API.

## Outcome
- Cross-tenant data leaks completely prevented (404/403 returned).
- SLA attributes automatically appended to Ticket models.
- Frontend successfully fetches and renders tenant-specific ticket boards.
