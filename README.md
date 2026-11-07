# Restaurant Management & Ordering System

A Laravel 13 web application showcasing relational database design, MVC architecture, user authentication, and reliable restaurant-order workflows. Built as an educational software-engineering and research portfolio project.

> **Status:** functional ordering MVP. Administrative tools and reproducible database benchmarks are on the roadmap; they have **not** been implemented or measured yet.

## Features

- Public food/drink menu organized by category
- Account registration, login, and logout
- Authenticated order creation with server-side pricing and validation
- Order history and owner-only order details
- Atomic order writing in database transactions
- Menu availability checks and historical unit-price snapshots
- Re-runnable sample-data seeder and feature tests
- Responsive Blade UI without extra frontend dependencies

## Stack

- PHP 8.5, Laravel 13, Blade, Eloquent ORM
- MariaDB (MySQL-compatible) for local development; SQLite in feature tests
- PHPUnit / Laravel Feature tests
- Plain CSS; no Node.js build step for this MVP

## Getting started

This is an **incremental update** for an existing Laravel 13 application. See [setup steps](docs/MVP_SETUP.md) for the commands. Make sure the database migration for `categories`, `menu_items`, `orders`, and `order_items` has already run.

```powershell
$php = 'P:\app\41 apps inside VS CODE\php85\php.exe'
& $php artisan optimize:clear
& $php artisan migrate
& $php artisan db:seed --class=RestaurantDemoSeeder
& $php artisan test --filter=RestaurantWorkflowTest
& $php artisan serve
```

Open `http://127.0.0.1:8000`, register a test user, select menu quantities, and place an order. Sample data consists of fictional menu entries; no real money or payment service is used. Example menu prices are unit-less demo amounts.

## Architecture

```text
Browser / Blade UI
     | CSRF-protected web routes
     v
Controllers (auth, menu, orders)
     | input validation, access checks
     v
Eloquent models & MariaDB
     | transaction + row locks
     v
orders <--- order_items ---> menu_items ---> categories
  |
 users
```

Table design stores `order_items.unit_price` as a snapshot of the menu price at the time of the transaction. Server-side totals are calculated using integer minor units to avoid floating-point accumulation. `orders.user_id` checks restrict read access to each account's own records.

## Testing

Run `php artisan test --filter=RestaurantWorkflowTest` with a supported PHP executable. Tests cover menu display, guest restrictions, server-derived totals, unavailable items, and ownership. Actual result logs and performance measurements will be added after executing tests on the local machine.

## Roadmap

1. Authorized admin/staff panel for menu CRUD and order status changes
2. More validation and authorization tests; automated CI
3. Reproducible performance experiments with generated orders, indexing experiments, SQL query plans and p50/p95 latency charts
4. English technical report and reproducibility instructions

See [research roadmap](docs/ROADMAP.md). **Do not treat planned features or benchmark hypotheses as completed findings.**

## Security notes

This is a local research/learning MVP, **not production-ready**. Never commit `.env` or credentials. Add email verification, hardened rate limits, deployment configuration, database upgrades, and staff authorization before exposure to a real public audience. MariaDB 10.4 from an old XAMPP installation should not be used for public deployment.
