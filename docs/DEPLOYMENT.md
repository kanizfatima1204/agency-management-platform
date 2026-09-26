# Deployment

## Local setup

Requirements: PHP 8.2+, Composer 2, Node.js 20+, MySQL 8 compatible database.

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
# Configure DB_* in .env and create the agency_os database first.
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

The live demo is https://agency-management-platform-production.up.railway.app. Demo users (all seeded with password `password`): `admin@agency.test`, `client@agency.test`, `team@agency.test`, `intern@agency.test`. These shared credentials are for evaluation only. Do not store real client data until they are disabled/replaced and production account provisioning is implemented.

## Production release

Set production environment values and `APP_DEBUG=false`; install Composer dependencies with `--no-dev --optimize-autoloader`; run `npm ci && npm run build`; run `php artisan migrate --force`; serve only the `public/` directory behind HTTPS; configure writable `storage/` and `bootstrap/cache/`; keep `storage/app/private` off the web root; configure backups, logs, scheduler and queue workers as adopted. Never commit `.env` or deployment secrets.

Live deployment requires a configured hosting account, database, domain and secrets; none are provisioned by this repository.
