# PulseDesk 🚀

**PulseDesk** is a modern, multi-tenant Helpdesk and Ticketing SaaS platform built for the NMG Forge2 Hackathon. It provides a seamless experience for Requesters, Agents, and Administrators to manage support operations, enforce SLAs, and track activity logs in real-time.

## 🔗 Live Demo
- **Frontend (Vercel):** [https://forge2-shivansh.vercel.app](https://forge2-shivansh.vercel.app)
- **Backend API (Render):** [https://pulsedesk-api-nnjd.onrender.com](https://pulsedesk-api-nnjd.onrender.com)

## 📸 Screenshots

<details>
<summary><b>Click to expand and view all screenshots</b></summary>
<br>

![Screenshot](docs/screenshots/Screenshot%202026-06-27%20125044.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20125130.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20125350.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20125627.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20125657.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20135402.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140317.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140329.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140342.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140358.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140409.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140436.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140446.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140505.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140750.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140814.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140840.png)
![Screenshot](docs/screenshots/Screenshot%202026-06-27%20140905.png)

</details>

## ✨ Key Features
- **Multi-Tenancy:** Secure data isolation by Organization. Users only see tickets belonging to their tenant workspace.
- **Role-Based Access Control (RBAC):** Distinct roles for `Admin`, `Agent`, and `Customer` with strict API authorization gates.
- **SLA Monitoring Engine:** Automated SLA breach detection based on dynamic organizational SLA policies.
- **Conversation Threading:** Public replies and internal admin-only notes.
- **Activity Timeline:** Complete audit trail tracking status changes, priority shifts, and re-assignments.
- **Modern UI:** Built with React, TailwindCSS, and Lucide icons for a premium aesthetic.

## 🛠️ Technology Stack
### Frontend
- **Framework:** React 19 + Vite
- **Styling:** TailwindCSS
- **State/Fetching:** React Query (TanStack) & Axios
- **Routing:** React Router DOM

### Backend
- **Framework:** Laravel 11/12 (PHP 8.4)
- **Database:** PostgreSQL (Production) / MySQL (Testing)
- **Auth:** Laravel Sanctum (Token-based Auth)
- **Deployment:** Dockerized Environment

### Infrastructure
- **CI/CD:** GitHub Actions (Automated testing with MySQL & PHPUnit)
- **Deployment:** Vercel (Frontend), Render (Backend via Blueprint `render.yaml`)

## 🚦 Local Setup

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
# Ensure .env contains VITE_API_URL=http://localhost:8000/api
npm run dev
```

## 🧪 Testing
The backend features an exhaustive PHPUnit test suite ensuring strict multi-tenancy isolation and authorization logic.
```bash
cd backend
php artisan test
```
