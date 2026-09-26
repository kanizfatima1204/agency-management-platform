# Security and error handling

## Implemented

- Laravel password hashing, session authentication, session ID rotation at login, invalidation at logout, CSRF middleware.
- Login throttle: five attempts per minute per email/IP key.
- Server-side role checks and project ownership/membership checks on read and mutation routes.
- Validation for project, task, payment, message and file input; file names are stored as metadata, never used as a storage path.
- Private local file disk with authorized controller downloads and 10 MB/type allowlist.
- Inertia shares only safe user fields; password and remember token are hidden on the model.

## Error behavior

Validation errors return to the form with field-level errors. Authentication redirects to login; authorization returns 403; unknown resources return 404; rate limits return 429. Unexpected exceptions use Laravel's exception pipeline. In production disable debug output, centralize structured logs, and show a generic error page with a request ID.

## Before production

- Enforce HTTPS, secure/HTTP-only/SameSite cookies, trusted host/proxy configuration and `APP_DEBUG=false`.
- Replace seeded demo credentials; disable public demo users and add invitation, password reset, email verification and MFA workflows.
- Add a proper user-management permission model, audit trail for finance and role changes, and retention/deletion policy.
- Add virus scanning and content-disposition hardening for uploaded files; consider per-tenant quotas.
- Add database backups, secret management, dependency/security updates, monitoring and incident response.
- Configure login throttling store consistently across app instances and test abuse controls.
