# Sprint 0 — Foundation & Data Layer
**Goal:** Repo setup + DB schema + migrations + seeders
**Status:** Complete

## Issues
1. Initialize Laravel 11 backend and React 19 + Vite frontend
2. Configure MySQL 8 database connections
3. Create all migrations (organizations, users, tickets, conversations, activity_logs, sla_policies)
4. Implement DatabaseSeeder (1 org, 1 admin, 2 agents, 2 customers, 12 tickets)

## Outcome
- php artisan migrate --seed runs successfully
- All tables created with proper foreign keys
- Demo data seeded
