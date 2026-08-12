# Safari Travel Booking Platform

A full-featured safari travel booking and trip-management web application built with **Laravel 12**. It powers a public-facing safari company website plus an admin back office for managing destinations, safaris, accommodation, experiences, itineraries, leads, client proposals, bookings, payments and more.

## Tech Stack

- **Backend:** PHP 8.2 / Laravel 12
- **Frontend:** Blade + Vite (Laravel Breeze-style auth)
- **Database:** MySQL (with `database/database.sqlite` supported for local dev)
- **Payments:** Stripe + Flutterwave
- **PDF generation:** `barryvdh/laravel-dompdf`
- **Email/mail:** Built-in mail with incoming mail (IMAP) fetching via artisan command

## Key Features

### Public website
- Destinations, safaris, accommodation, experiences and itineraries pages with slugs/seo
- Golf & "Tee Off" pages (per country)
- Blog, FAQs, About, Contact pages (CMS-managed)
- Enquiry + newsletter subscription
- Visitor live chat (`/chat/{token}`)
- Multi-language support (`/language/{locale}`)
- Public booking form + client proposal viewer (accept / request changes / download PDF)

### Admin back office (`/admin`)
- Dashboard
- Leads & lead tracking (with follow-ups, notes, tags, status logs, tasks, history)
- Bookings, flights & accommodation booking
- Itinerary builder (V2), templates and pricing
- Proposals with versioning, change requests, evaluations and acceptance
- CMS pages, content blocks & website settings
- Payments, payment links, supplier invoices & exchange rates
- Incoming mail inbox + mail settings
- Audit log & two-factor authentication

## Getting Started

### Requirements
- PHP >= 8.2 with required extensions
- Composer
- Node.js + npm (for Vite assets)
- MySQL (or SQLite for quick local dev)

### Installation

```bash
git clone https://github.com/biznapoa222/safari.git
cd safari
composer install
cp .env.example .env      # then edit your DB / mail / payment keys
php artisan key:generate
npm install && npm run build
php artisan migrate --seed
php artisan serve
```

The app will be available at `http://localhost:8000`.

### Local development (with asset watcher + queue + logs)

```bash
composer run dev
```

This runs the Vite dev server, queue worker, `php artisan serve` and live logs together.

### Useful artisan commands

```bash
php artisan pail          # live log tail
php artisan queue:work    # process queued jobs
php artisan fetch:incoming-mail   # pull inbound emails from IMAP
```

## Project Structure

```
app/Http/Controllers/   # Web controllers (public + admin)
app/Models/             # Eloquent models
routes/web.php          # All web routes (public + /admin group)
resources/views/        # Blade templates
database/migrations/    # Schema
database/seeders/       # Seed data
public/                 # Public assets (images, build output)
```

## Branching & Workflow

- **`main`** is the stable branch. Work on feature branches and open a pull request when done.
- Keep `.env` out of the repo — it is gitignored. Never commit real credentials.
- Always run `composer run dev` or at least `npm run dev` while working on views so Vite picks up asset changes.

## Environment Variables (`.env`)

Copy `.env.example` and set at minimum:

| Variable | Purpose |
| --- | --- |
| `APP_KEY` | Generated with `php artisan key:generate` |
| `DB_*` | Database connection |
| `STRIPE_KEY` / `STRIPE_SECRET` | Stripe payments |
| `FLUTTERWAVE_*` | Flutterwave payments |
| `MAIL_*` | Outbound mail |
| `IMAP_*` | Incoming mail fetching |

## Tests

```bash
php artisan test
```

## License

MIT (framework skeleton). Project-specific code is proprietary to the owner.
