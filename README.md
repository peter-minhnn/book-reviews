# Book Review — Pure PHP MVC

A book review website built with pure PHP (no framework), PostgreSQL, Tailwind CSS, and Alpine.js.

## Stack

- **PHP 8.2** — Pure MVC, PSR-4 autoload, PDO with prepared statements
- **PostgreSQL 16** — Relational data with foreign keys and constraints
- **Tailwind CSS v4 + Alpine.js** — Styled UI with clay/glass morphism
- **Docker** — Nginx, PHP-FPM, PostgreSQL, pgAdmin

## Quick Start

```bash
# Start all services (first run creates the database and seeds demo data)
docker compose up -d --build

# Open the app
open http://localhost:8080

# Seed demo data (only needed if starting with an empty database)
docker compose exec app php scripts/seed.php
```

## Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| User | user@example.com | password |
| User | jane@example.com | password |
| User | bob@example.com | password |

## Features

- **Public**: Browse books with search, category filter, sort (newest/rating), AJAX pagination
- **Book detail**: Cover image, description, average rating, review list
- **Live reviews**: SSE-based real-time review stream on the home page
- **Auth**: Session-based login/register/logout with bcrypt password hashing
- **Reviews**: Create, update, delete (one review per user per book, star rating 1—5)
- **Favorites**: Add/remove books from personal list
- **Admin panel**: Dashboard stats, CRUD for books/categories/reviews/users, cover image upload
- **Security**: CSRF protection on all mutations, XSS prevention (htmlspecialchars), SQL injection prevention (prepared statements), IDOR checks

## Project Structure

```
book-review-php/
├── public/              # Document root (Nginx)
│   ├── index.php        # Front controller
│   └── uploads/covers/  # Uploaded book covers
├── src/
│   ├── Core/            # Framework: App, Router, Database (PDO), Session, Auth, View, Validator
│   ├── Controllers/     # 13 controllers (Auth, Books, Reviews, Favorites, Profile, SSE, Admin)
│   ├── Repositories/    # 6 PDO repositories with prepared statements
│   ├── Middleware/       # AuthMiddleware, AdminMiddleware, GuestMiddleware
│   └── helpers.php       # e(), route(), csrf_field(), session(), auth(), view()
├── views/               # 27 PHP templates
├── config/              # app.php, database.php, session.php
├── routes/web.php       # All ~45 route definitions
├── database/            # schema.sql, seed.sql
├── scripts/             # seed.php (PHP seeder)
├── bootstrap/app.php    # Env loader, error handling, app bootstrap
├── docker/              # Dockerfile, Nginx config
└── docker-compose.yml
```

## Admin Panel

Navigate to `/admin` after logging in as an admin user. Manage:

- **Books**: Create, edit, delete with cover image upload (JPEG/PNG/WebP, max 2MB)
- **Categories**: Create, edit, delete (cascades to books)
- **Reviews**: View and delete any review
- **Users**: Edit name/email/role/password, delete users (cannot self-delete or self-demote)

## Resetting Data

```bash
# Tear down and remove the database volume
docker compose down -v

# Rebuild — schema and seed run automatically
docker compose up -d --build
```

## Routes

| URL | Auth | Description |
|-----|------|-------------|
| `/` | Public | Home page with latest books, top rated, live reviews |
| `/books` | Public | Book browser with AJAX search/filter/sort |
| `/books/{id}` | Public | Book detail with reviews |
| `/login` / `/register` | Guest | Authentication |
| `/favorites` | Auth | User's favorite books |
| `/profile` | Auth | Edit profile, change password, delete account |
| `/dashboard` | Auth | Logged-in landing page |
| `/admin` | Admin | Dashboard with stats |
| `/admin/books` | Admin | Book CRUD |
| `/admin/categories` | Admin | Category CRUD |
| `/admin/reviews` | Admin | Review management |
| `/admin/users` | Admin | User management |
| `/events/latest-reviews` | Public | SSE review stream |
