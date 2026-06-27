# PulseDesk 🚀

## Overview

PulseDesk is a modern, production-ready, multi-tenant Helpdesk and Ticketing SaaS platform built for the NMG Forge2 Hackathon.

The platform enables organizations to efficiently manage customer support operations through secure tenant isolation, role-based access control, SLA monitoring, conversation management, and comprehensive activity tracking.

---

## Problem Statement

Many organizations struggle with fragmented customer support systems that lack proper tenant isolation, SLA enforcement, and centralized communication.

PulseDesk addresses these challenges by providing a scalable SaaS helpdesk solution where multiple organizations can securely operate within isolated workspaces while maintaining efficient support workflows.

---

## Live Demo

**Frontend (Vercel):**

https://forge2-shivansh.vercel.app

**Backend API (Render):**

https://pulsedesk-api-nnjd.onrender.com

**GitHub Repository:**

https://github.com/Shivansh1146/forge2_shivansh

---

## Key Features

### 🔐 Multi-Tenancy

* Complete tenant isolation using Organizations.
* Users can only access resources belonging to their organization.
* Prevents unauthorized cross-organization access.

### 👥 Role-Based Access Control (RBAC)

Three distinct user roles:

* **Customer**

  * Create and view personal tickets.

* **Agent**

  * Manage assigned tickets.
  * Update statuses.
  * Communicate with customers.

* **Admin**

  * Full organizational control.
  * Manage tickets, users, and assignments.

### ⏱️ Automated SLA Monitoring

* Dynamic SLA policies per organization.
* Automatic breach detection.
* Dashboard indicators for breached tickets.
* Priority-based resolution thresholds.

### 💬 Advanced Conversation System

* Public customer replies.
* Internal team notes.
* Collaborative support workflow.

### 📜 Activity Timeline & Audit Trail

Tracks:

* Ticket creation
* Status changes
* Priority updates
* Reassignments
* Administrative actions

### 🎨 Modern UI/UX

* Clean and responsive interface.
* Built using React and TailwindCSS.
* Optimized for desktop and mobile devices.

---

## Architecture

Frontend (React + Vite)
⬇
REST API Communication
⬇
Laravel Backend
⬇
Authentication Layer (Sanctum)
⬇
PostgreSQL Database

---

## Technology Stack

### Frontend

* React 19
* Vite
* TailwindCSS
* React Query (TanStack)
* Axios
* React Router DOM
* Lucide Icons

### Backend

* Laravel 11/12
* PHP 8.4
* RESTful API Architecture

### Database

* PostgreSQL (Production)
* MySQL (CI Testing)
* SQLite (Local Development)

### Authentication

* Laravel Sanctum

### Infrastructure

* Docker
* Render
* Vercel

### CI/CD

* GitHub Actions
* PHPUnit

---

## Database Design

### Organizations

Root entity of the multi-tenant architecture.

### Users

Belongs to Organization.

Roles:

* Admin
* Agent
* Customer

### Tickets

Contains:

* Subject
* Description
* Status
* Priority
* Assignee
* Requester

### Ticket Conversations

Stores:

* Public replies
* Internal notes

### Activity Logs

Maintains complete audit history.

### SLA Policies

Defines response and resolution thresholds.

---

## Automated Testing

The platform includes an extensive automated test suite covering:

* Authentication edge cases
* Authorization checks
* Multi-tenancy isolation
* SLA breach calculations
* Dashboard metrics
* Role restrictions

### Example Test Cases

* Duplicate registrations.
* Invalid login attempts.
* Unauthorized access attempts.
* Cross-tenant ticket access prevention.
* SLA time-travel simulations using Carbon.

Run tests:

```bash
cd backend
php artisan test
```

---

## CI/CD Pipeline

GitHub Actions automatically executes on every push.

Pipeline tasks:

1. Provision MySQL.
2. Install dependencies.
3. Generate Laravel APP_KEY.
4. Run database migrations.
5. Execute PHPUnit suite.
6. Validate build success.

---

## Deployment

### Frontend

* Hosted on Vercel.

### Backend

* Hosted on Render using Docker.

### Database

* PostgreSQL managed by Render.

---

## Local Setup

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Frontend

```bash
cd frontend
npm install
npm run dev
```

---

## Screenshots

<details>
<summary><b>Click to expand and view all screenshots</b></summary>
<br>

![Screenshot](screenshots/Screenshot%202026-06-27%20125044.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20125130.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20125350.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20125627.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20125657.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20135402.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140317.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140329.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140342.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140358.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140409.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140436.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140446.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140505.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140750.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140814.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140840.png)
![Screenshot](screenshots/Screenshot%202026-06-27%20140905.png)

</details>

---

## Challenges Faced

* Designing strict multi-tenant isolation.
* Implementing secure role-based authorization.
* Handling SPA routing in Vercel.
* Deploying Laravel with Docker on Render.
* Building reliable SLA breach detection.

---

## Future Scope

* Email notifications.
* WebSocket real-time updates.
* File attachments.
* Analytics dashboard.
* AI-powered ticket classification.
* Automatic ticket assignment.
* Knowledge base integration.

---

## Team

Developed by **Shivansh Jaiswal** for the **NMG Forge2 Hackathon**.
