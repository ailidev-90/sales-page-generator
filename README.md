# AI Sales Page Generator

A Laravel frontend/fullstack assessment project that turns raw product or service details into a polished sales landing page. The app uses authenticated CRUD flows, Blade, Tailwind CSS, Vite, SQLite for local development, and an OpenAI integration with a built-in mock fallback.

## Features

- Register, login, and logout with Breeze-style authentication.
- Protected sales page generator at `/sales-pages/create`.
- Validated product input form with template and tone options.
- AI generation of structured JSON content:
  - headline
  - subheadline
  - product description
  - benefits
  - features
  - social proof placeholder
  - pricing display
  - CTA text
- Safe JSON parsing and normalized content before rendering.
- Fallback mock generator when `OPENAI_API_KEY` is missing or the API request fails.
- Saved pages list with search by product name or generated headline.
- Edit and regenerate flow.
- Delete flow scoped to the authenticated owner.
- Responsive landing page previews for modern, elegant, and bold templates.
- Standalone HTML export with Tailwind CDN.

## Tech Stack

- Laravel 11
- Laravel Breeze-style auth scaffolding
- PHP 8.4+
- Blade
- Tailwind CSS
- Vite
- SQLite by default, MySQL-compatible configuration available
- OpenAI API through Laravel HTTP client

## Setup

Install dependencies:

```bash
composer install
npm install
```

Create environment configuration if needed:

```bash
cp .env.example .env
php artisan key:generate
```

For SQLite local development:

```bash
touch database/database.sqlite
php artisan migrate --seed
```

The `--seed` flag creates the optional demo account shown below. If you already ran migrations without seeding, run `php artisan db:seed`.

Run the app:

```bash
npm run dev
php artisan serve
```

Open `http://localhost:8000`.

## Environment

SQLite is configured by default:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

To use MySQL, update:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai_sales_pages
DB_USERNAME=root
DB_PASSWORD=
```

Optional OpenAI configuration:

```env
OPENAI_API_KEY=your-api-key
OPENAI_MODEL=gpt-4o-mini
```

## AI Generation

`App\Services\AiSalesPageService` sends validated product data to the OpenAI chat completions endpoint and asks for a strict JSON object. The service never renders raw model text. It decodes JSON, strips accidental code fences, validates expected fields, normalizes arrays, and returns a predictable content structure for storage.

## Fallback Behavior

If no API key is configured, the OpenAI request fails, or the response cannot be parsed as valid JSON, the service creates high-quality mock content from the submitted product details. This keeps the demo fully functional without external API access.

## Database

Generated pages are stored in the `sales_pages` table and linked to `users`.

Run migrations with:

```bash
php artisan migrate --seed
```

The generated sales page content is stored in the `generated_content` JSON column.

## Deployment Notes

- Run `composer install --no-dev --optimize-autoloader`.
- Run `npm run build`.
- Set a real `APP_KEY`.
- Configure production database credentials.
- Set `OPENAI_API_KEY` for live AI generation.
- Run `php artisan migrate --force`.
- Cache configuration and routes if desired:

```bash
php artisan config:cache
php artisan route:cache
```

## Published Prototype Deployment

For the assessment requirement, deploy the app to a public web service and connect it to a managed database. The repository includes a `Dockerfile` and startup script for container deployment.

Recommended quick path: Railway + MySQL.

1. Push this repository to GitHub.
2. Create a new Railway project from the GitHub repository.
3. Add a MySQL database service in the same Railway project.
4. Set these variables on the web app service:

```env
APP_NAME="AI Sales Page Generator"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key
APP_URL=https://your-public-url

DB_CONNECTION=mysql
LOG_CHANNEL=stderr
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
RUN_MIGRATIONS=true
RUN_SEEDER=true

OPENAI_API_KEY=
OPENAI_MODEL=gpt-4o-mini
```

`RUN_SEEDER` defaults to `true` in the Docker startup script so the demo account is created automatically for the published prototype. You can set `RUN_SEEDER=false` later if you do not want demo credentials in production.

Railway exposes MySQL variables named `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, and `MYSQLPASSWORD`. The app reads those automatically, so you do not need to manually duplicate them into Laravel `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.

Do not copy local `.env` values into Railway. Remove or override these local-only values if they exist in the Railway web app service:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
DB_DATABASE=/Users/...
LOG_CHANNEL=stack
```

The Docker build removes `.env` from the image. The startup script defaults to production-safe values, and if Railway MySQL variables are present it forces `DB_CONNECTION=mysql` and maps the Railway `MYSQL*` variables into Laravel `DB_*` variables.

The current Railway MySQL variables are compatible with this app:

```env
MYSQLDATABASE="${{MYSQL_DATABASE}}"
MYSQLHOST="${{RAILWAY_PRIVATE_DOMAIN}}"
MYSQLPASSWORD="${{MYSQL_ROOT_PASSWORD}}"
MYSQLPORT="3306"
MYSQLUSER="root"
MYSQL_URL="mysql://${{MYSQLUSER}}:${{MYSQL_ROOT_PASSWORD}}@${{RAILWAY_PRIVATE_DOMAIN}}:3306/${{MYSQL_DATABASE}}"
```

Use the private host/URL for the deployed app service. `MYSQL_PUBLIC_URL` is only needed for connecting from your local machine.

Generate a production app key locally with:

```bash
php artisan key:generate --show
```

After deployment, open the generated public URL and verify:

- Register/login works.
- `/sales-pages/create` can generate a page.
- Saved pages persist after refresh.
- Preview and export HTML work.
- The app is using MySQL, not localhost SQLite.

If an older deployment shows `AH00534: More than one MPM loaded`, force a fresh rebuild and redeploy. The current Dockerfile no longer uses Apache; it runs Laravel directly with `php artisan serve` on Railway's `$PORT`, so Apache MPM configuration is no longer involved.

## Demo Account

The seeder creates an optional demo user:

```text
Email: demo@example.com
Password: password
```

Run it with:

```bash
php artisan db:seed
```
