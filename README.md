# Barqun SCM

A web-based **Supply Chain Management (SCM)** application for managing products, suppliers, customers, orders, warehouses, and stock movements in one dashboard.

The project is split into a Laravel REST API (`backend/`) and a Vue + Vuetify single-page application (`frontend/`).

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
  - [1. Clone the Repository](#1-clone-the-repository)
  - [2. Backend Setup](#2-backend-setup)
  - [3. Frontend Setup](#3-frontend-setup)
- [Environment Variables](#environment-variables)
- [Default Administrator](#default-administrator)
- [API Overview](#api-overview)
- [Available Scripts](#available-scripts)
- [Testing and Verification](#testing-and-verification)
- [Security Notes](#security-notes)
- [Troubleshooting](#troubleshooting)
- [Known Limitations](#known-limitations)
- [Roadmap](#roadmap)

---

## Features

- **Authentication** — token-based login and registration using Laravel Sanctum.
- **Role-Based Access Control (RBAC)** — roles and granular permissions powered by Spatie Laravel Permission.
- **Dashboard** — quick access to the main SCM modules with light/dark theme support.
- **Product Management** — create, read, update, and delete products with price, stock, and description.
- **Supplier and Customer Management** — maintain business partner records.
- **Order Management** — record product orders, quantities, dates, and order status.
- **Warehouse Management** — manage warehouses, capacity, and locations.
- **Stock Movements** — stock in, stock out, and stock opname (audit) records.
- **User and Role Management** — manage application users, roles, and permissions.
- **Responsive UI** — Vuetify 2 components with a Vue 2 single-page application.

---

## Tech Stack

### Backend

| Component | Version / Technology |
| --- | --- |
| Language | PHP 8.1+ (tested on PHP 8.2) |
| Framework | Laravel 8.83.29 |
| Authentication | Laravel Sanctum 2.15 |
| Authorization | Spatie Laravel Permission 5.11 |
| Database | MySQL 5.7+ / MySQL 8 |
| Dependency Manager | Composer 2 |
| Testing | PHPUnit 9.6 |

### Frontend

| Component | Version / Technology |
| --- | --- |
| Framework | Vue 2.7.16 |
| UI Library | Vuetify 2.7 |
| Routing | Vue Router 3.6 |
| State Management | Vuex 3.6 |
| HTTP Client | Axios 1.21 |
| Build Tool | Vite 4.5 |
| Styling | Sass / SCSS |
| Linting | ESLint 7 + eslint-plugin-vue |

> **Runtime status:** Laravel 8 and Vue 2 have both reached end-of-life upstream. The project currently stays on these major versions to preserve existing behavior. See [Roadmap](#roadmap) for the recommended upgrade path.

---

## Project Structure

```text
scm-barqun/
├── backend/                    # Laravel REST API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/api # API controllers
│   │   │   ├── Middleware/
│   │   │   └── Requests/       # Form request validation
│   │   ├── Models/             # Eloquent models
│   │   └── Traits/Uuid.php     # UUID model trait
│   ├── config/                 # Laravel configuration
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/api.php          # API route definitions
│   ├── tests/                  # PHPUnit feature and unit tests
│   ├── .env.example
│   └── composer.json
├── frontend/                   # Vue single-page application
│   ├── public/                 # Static assets
│   ├── src/
│   │   ├── components/         # Reusable and module components
│   │   ├── plugins/            # Vuetify configuration
│   │   ├── router/             # Route definitions and auth guard
│   │   ├── services/api.js     # Axios instance and token handling
│   │   ├── store/              # Vuex store
│   │   └── views/              # Page-level views
│   ├── .env.example
│   ├── package.json
│   └── vite.config.js
├── .editorconfig
├── .gitignore
└── README.md
```

---

## Prerequisites

Install the following tools before starting:

- **PHP 8.1 or newer** with the `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, and `bcmath` extensions enabled.
- **Composer 2**
- **MySQL 5.7+ / 8** (or MariaDB 10.3+)
- **Node.js 18+** and **npm 9+** (Node.js 20 LTS is recommended)
- **Git**

Verify the installation:

```bash
php -v
composer --version
node -v
npm -v
mysql --version
```

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/ekarufufufu/scm-barqun.git
cd scm-barqun
```

### 2. Backend Setup

```bash
cd backend

# Install PHP dependencies
composer install

# Create the local environment file
copy .env.example .env        # Windows
# cp .env.example .env        # macOS / Linux

# Generate the application key
php artisan key:generate
```

Create the database before running migrations:

```sql
CREATE DATABASE scm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Update the database and administrator values in `backend/.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=scm
DB_USERNAME=root
DB_PASSWORD=

ADMIN_NAME="Administrator"
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=change-this-password
```

Run migrations and seed the initial data:

```bash
php artisan migrate --seed
```

Start the API server:

```bash
php artisan serve --host=127.0.0.1 --port=8081
```

The API is now available at `http://127.0.0.1:8081/api`.

### 3. Frontend Setup

Open a new terminal:

```bash
cd frontend

# Install JavaScript dependencies
npm install

# Create the local environment file
copy .env.example .env        # Windows
# cp .env.example .env        # macOS / Linux

# Start the development server
npm run dev
```

Open `http://127.0.0.1:8080` in your browser.

The frontend reads the API base URL from `VITE_API_BASE_URL`:

```dotenv
VITE_API_BASE_URL=http://127.0.0.1:8081/api
```

---

## Environment Variables

### Backend (`backend/.env`)

| Variable | Purpose | Example |
| --- | --- | --- |
| `APP_NAME` | Application name | `"Barqun SCM"` |
| `APP_ENV` | Runtime environment | `local` |
| `APP_KEY` | Laravel encryption key | Generated by `php artisan key:generate` |
| `APP_DEBUG` | Verbose error output | `true` locally, `false` in production |
| `APP_URL` | Backend base URL | `http://127.0.0.1:8081` |
| `DB_*` | MySQL connection settings | See installation steps |
| `CORS_ALLOWED_ORIGINS` | Comma-separated frontend origins | `http://127.0.0.1:8080` |
| `SANCTUM_STATEFUL_DOMAINS` | Sanctum stateful hosts | `127.0.0.1:8080` |
| `ADMIN_NAME` | Seeded administrator name | `"Administrator"` |
| `ADMIN_EMAIL` | Seeded administrator email | `admin@example.com` |
| `ADMIN_PASSWORD` | Seeded administrator password | Set a strong password |

If `ADMIN_PASSWORD` is left empty, the seeder generates a random password and prints it to the console during `php artisan migrate --seed`.

### Frontend (`frontend/.env`)

| Variable | Purpose | Example |
| --- | --- | --- |
| `VITE_API_BASE_URL` | Base URL of the Laravel API | `http://127.0.0.1:8081/api` |

> Never commit `.env` files. Both backend and frontend `.env` files are ignored by Git; only `.env.example` files are tracked.

---

## Default Administrator

The administrator account is created by `Database\Seeders\AdminSeeder` using the `ADMIN_*` values in `backend/.env`.

After seeding, log in through the frontend using the configured email and password. Change the password immediately in any non-local environment.

---

## API Overview

All protected endpoints require a Sanctum bearer token:

```http
Authorization: Bearer <access_token>
Accept: application/json
```

| Method | Endpoint | Description |
| --- | --- | --- |
| `POST` | `/api/register` | Register a new account |
| `POST` | `/api/login` | Authenticate and receive a token |
| `GET` | `/api/profile` | Get the authenticated user |
| `POST` | `/api/logout` | Revoke the authenticated user's tokens |
| `GET/POST` | `/api/product` | List or create products |
| `GET/PUT/DELETE` | `/api/product/{id}` | Show, update, or delete a product |
| `GET/POST` | `/api/supplier` | List or create suppliers |
| `GET/PUT/DELETE` | `/api/supplier/{id}` | Show, update, or delete a supplier |
| `GET/POST` | `/api/customer` | List or create customers |
| `GET/PUT/DELETE` | `/api/customer/{id}` | Show, update, or delete a customer |
| `GET/POST` | `/api/inventory` | List or create warehouses |
| `GET/PUT/DELETE` | `/api/inventory/{id}` | Show, update, or delete a warehouse |
| `GET/POST` | `/api/order` | List or create orders |
| `GET/PUT/DELETE` | `/api/order/{id}` | Show, update, or delete an order |
| `GET/POST` | `/api/stockin` | List or create stock-in records |
| `GET/POST` | `/api/stockout` | List or create stock-out records |
| `GET/POST` | `/api/stockopname` | List or create stock opname records |
| `GET/POST` | `/api/role` | List or create roles |
| `GET/POST` | `/api/user` | List or create users |

Each resource also supports `GET/PUT/DELETE /api/{resource}/{uuid}` for detail, update, and delete operations.

Login and registration are rate-limited. All other protected routes use the authenticated user's permissions.

---

## Available Scripts

### Backend

```bash
composer install                 # Install dependencies
php artisan serve --port=8081    # Run the API server
php artisan migrate --seed       # Migrate and seed the database
php artisan test                 # Run PHPUnit tests
composer audit                   # Check dependency advisories
```

### Frontend

```bash
npm install          # Install dependencies
npm run dev          # Start the Vite dev server
npm run build        # Build for production into dist/
npm run preview      # Preview the production build
npm run lint         # Lint and auto-fix source files
npm run lint:check   # Lint without modifying files
```

---

## Testing and Verification

Backend:

```bash
cd backend
php artisan test
```

Frontend:

```bash
cd frontend
npm run lint:check
npm run build
```

The backend test suite uses an in-memory SQLite database, so it does not require a running MySQL server.

---

## Security Notes

- **Rotate leaked credentials.** Earlier revisions of this repository tracked `backend/.env`, including an application key. Remove the file from Git history with a tool such as `git filter-repo` or BFG Repo-Cleaner, then generate a new `APP_KEY` and rotate any database or mail credentials that were exposed.
- **Never commit secrets.** `.env`, `vendor/`, `node_modules/`, build output, logs, and local databases are now ignored.
- **Remove demo tokens.** Hardcoded Sanctum bearer tokens were removed from the frontend. Tokens are now read from `localStorage` and attached by the shared Axios instance in `src/services/api.js`.
- **Production tokens.** For a production deployment, consider moving authentication to secure, `HttpOnly` cookies instead of `localStorage`.
- **CORS.** Restrict `CORS_ALLOWED_ORIGINS` to the actual frontend origin in production. Do not use `*`.
- **Debug mode.** Set `APP_DEBUG=false` and `APP_ENV=production` in production.
- **Seed data.** Demo suppliers, customers, products, and warehouses are for local development only.

---

## Troubleshooting

**CORS error in the browser**

Set `CORS_ALLOWED_ORIGINS` in `backend/.env` to the exact frontend origin (including protocol and port), then run:

```bash
php artisan config:clear
```

**`401 Unauthorized` after logging in**

Make sure `VITE_API_BASE_URL` points to the correct backend and that the token is present in browser `localStorage` under the key `token`.

**Database connection error**

Confirm MySQL is running, the `scm` database exists, and `DB_*` values in `backend/.env` are correct.

**Missing application key**

```bash
cd backend
php artisan key:generate
```

**Port already in use**

Start the backend or frontend on a different port and update the corresponding `.env` value.

---

## Known Limitations

- **End-of-life frameworks.** Laravel 8 and Vue 2/Vuetify 2 are no longer maintained upstream. `composer audit` and `npm audit` still report advisories that can only be resolved by major framework migrations.
- **Stock movements are records only.** Stock-in, stock-out, and stock opname endpoints store movement records; they do not automatically recalculate product stock or validate available quantities.
- **No pagination.** List endpoints return all records, which is acceptable for the demo data set but not for large production tables.
- **Local development seed data.** The seeded suppliers, customers, products, and warehouses are demo data and should not be used in production.

---

## Roadmap

- Upgrade Laravel 8 to a supported LTS release (Laravel 10 or newer) and address dependency advisories reported by `composer audit`.
- Migrate Vue 2 + Vuetify 2 to Vue 3 + Vuetify 3.
- Add CI checks for linting, tests, and production builds.
- Add stock movement business rules (automatic stock updates, validation against available stock).
- Add pagination and server-side filtering for large data sets.

---

## License

No license file is currently included in this repository. Contact the repository owner before redistributing or using the code commercially.
