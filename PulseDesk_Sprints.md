# PulseDesk Sprint Plan

**Product:** PulseDesk (Multi-tenant SaaS help-desk)
**Stack:** Laravel 11, MySQL 8, Sanctum, React 19, Vite, Tailwind CSS
**Orchestrator:** Hermes (AI Product Owner)

---

## Sprint 0 — Foundation & Data Layer
**Goal:** Repo setup + DB schema + migrations + seeders

**Issue List:**
1. Initialize Laravel 11 backend and React 19 + Vite frontend repository structure.
2. Configure MySQL 8 database connections and environment variables.
3. Design and create migrations for Tenants (Organizations), Users (Admin/Agent/Customer), Tickets, and Conversations.
4. Implement Laravel seeders to generate the demo environment (1 Org, 1 Admin, 2 Agents, 2 Customers, 12 Tickets).

**Acceptance Criteria:**
- `php artisan migrate --seed` successfully runs and populates the database with the exact required demo data.
- Both Laravel API and React dev servers start successfully.
- Schema explicitly supports `organization_id` for multi-tenancy and stores all required ticket attributes (subject, description, status, priority, requester, assignee, tags).

---

## Sprint 1 — Authentication, Multi-Tenancy & Core API
**Goal:** Auth + multi-tenancy + Tickets CRUD API

**Issue List:**
1. Implement Laravel Sanctum authentication and token management.
2. Create role-based middleware (Admin, Agent, Customer) for authorization.
3. Implement Laravel Global Scopes on User and Ticket models to enforce `organization_id` multi-tenancy securely.
4. Develop REST API endpoints for complete Tickets CRUD operations.

**Acceptance Criteria:**
- API strictly isolates data by `organization_id` (a tenant cannot access another tenant's tickets).
- Customers can only read/update their own tickets; Agents/Admins can access all tickets within their org.
- Sanctum successfully authenticates API requests from the frontend or API testing tools.

---

## Sprint 2 — Frontend & Ticket Interactivity
**Goal:** Ticket conversations + filters + search + React frontend

**Issue List:**
1. Build React frontend architecture, routing, and Tailwind CSS design system.
2. Develop the main Ticket List UI with integrated text search and status/priority filters.
3. Build the Ticket Detail View.
4. Implement the Ticket Conversations API and UI component, supporting both "Public Replies" and "Internal Notes" (hidden from customers).

**Acceptance Criteria:**
- Agents can seamlessly switch between public replies and internal notes.
- Customers viewing their ticket do not see internal notes in the conversation thread.
- Ticket list can be actively filtered and searched via the frontend UI communicating with the Laravel API.

---

## Sprint 3 — Business Logic, Polish & CI/CD
**Goal:** Dashboard + SLA + activity log + CI/CD

**Issue List:**
1. Implement SLA policies & timers backend logic (tracking target resolution times based on priority).
2. Build Dashboard metrics view (Open tickets, SLA breaches, agent workloads).
3. Develop an Activity Log for tickets (recording assignment changes, status changes).
4. Implement basic automated routing/queues for new tickets.
5. Set up GitHub Actions CI for linting and running backend/frontend tests.

**Acceptance Criteria:**
- Tickets visually indicate SLA warnings or breaches based on timestamps.
- The ticket view shows a chronologically ordered Activity Log alongside the conversation.
- Committing to the repository successfully triggers the GitHub Actions CI pipeline.
- Dashboards display accurate aggregations for the logged-in agent/admin.
