# AgencyOS — Agency Management Platform MVP

Laravel 12, Vue 3, Inertia.js and MySQL foundation for agency project delivery.

## Included

- Admin, client, team member and intern role dashboards with server-side project scoping.
- Project creation and team assignment, task tracking and progress calculation.
- Admin payment records, project messages, and private project file upload/download.
- Session login/logout, CSRF protection, login throttling, input validation and migrations.
- Product/technical documentation in `docs/`.

## Local setup

Requirements: PHP 8.2+, Composer 2, Node.js 20+, MySQL 8 compatible server.

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
# Set DB_DATABASE, DB_USERNAME and DB_PASSWORD in .env; create that database.
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Open `http://127.0.0.1:8000` locally or https://agency-management-platform-production.up.railway.app for the live MVP. The seeded demo accounts all use `password`:

- `admin@agency.test`
- `client@agency.test`
- `team@agency.test`
- `intern@agency.test`

These shared demo credentials are enabled on the public MVP for evaluation. Do not use them for real client data; replace them and disable public demo seeding before production use.

## Documentation

- [PRD](docs/PRD.md) · [Architecture](docs/ARCHITECTURE.md) · [User flows](docs/USER-FLOWS.md)
- [Role matrix](docs/ROLE-MATRIX.md) · [ERD](docs/ERD.md) · [Database schema](docs/DATABASE-SCHEMA.md)
- [API/action reference](docs/API.md) · [Authentication](docs/AUTHENTICATION.md)
- [Security and errors](docs/SECURITY.md) · [Scalability](docs/SCALABILITY.md) · [Deployment](docs/DEPLOYMENT.md)

## Scope and deployment

This is a deployed working MVP foundation, not a fully hardened SaaS: it has no tenant isolation, gateway billing, external API, email/invitation workflow, or production account provisioning. See `docs/` for production follow-ups.
