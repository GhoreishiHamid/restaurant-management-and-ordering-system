# MVP setup — restaurant ordering app

This incremental patch is meant for the **existing** Laravel 13 project. It does **not** include or replace `.env`, existing migrations, dependencies, or `artisan`.

## Prerequisites

- PHP 8.5 CLI and Composer dependencies already installed
- MariaDB running in XAMPP
- Existing `restaurant_management` database with your four-table migration successfully run
- `.env` configured for MariaDB, with a valid `APP_KEY`

## Commands in a fresh VS Code PowerShell terminal

```powershell
Set-Location 'P:\02 GitHub\06 restaurant-management-and-ordering-system'
$php = 'P:\app\41 apps inside VS CODE\php85\php.exe'
& $php artisan optimize:clear
& $php artisan migrate
& $php artisan db:seed --class=RestaurantDemoSeeder
& $php artisan route:list
& $php artisan test --filter=RestaurantWorkflowTest
& $php artisan serve
```

Open http://127.0.0.1:8000; register an account, select menu quantities, place an order, and view its receipt. No additional npm tooling is required: CSS is served from `public/css/restaurant.css`.

## Security / limitations

- No payment gateway, production deployment, or admin dashboard yet.
    - The README shipped with this patch replaces Laravel’s starter README (installer backs it up).
- Demo content is not a commercial menu and prices have no fixed currency.
- Server validates quantities, availability, and uses database transactions; it does not trust totals sent by users.
- Registration has basic validation; prior to a public deployment, add email verification, production rate limits, monitoring, secure infrastructure, and role-based authorization for staff/admin functions.
- Never publish `.env` or other credentials. The project `.gitignore` should exclude it.
- MariaDB 10.4 from the user's XAMPP is for local testing only; upgrade before production.

## Next milestone

Add role-based admin CRUD for categories/menu items and an order-status workflow, then reproducible database query benchmarks with explicit methodology and hardware metrics.
