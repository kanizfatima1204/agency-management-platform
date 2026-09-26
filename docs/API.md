# API and web action reference

The MVP uses Laravel session authentication and Inertia page requests. The management endpoints below are same-origin web routes, not stateless REST APIs; they require a valid session and CSRF token. JSON clients should use `/api/me` as documented below. All IDs are numeric database IDs.

## Session API

| Method | Path | Access | Result |
|---|---|---|---|
| GET | `/api/me` | Authenticated | Current user fields: id, name, email, role |

## Inertia pages and actions

| Method | Path | Access | Input / behavior |
|---|---|---|---|
| GET | `/login` | Guest | Login page |
| POST | `/login` | Guest, 5 attempts/minute per email+IP | `email`, `password`; rotates session on success |
| POST | `/logout` | Authenticated | Invalidates session and CSRF token |
| GET | `/dashboard` | Authenticated | Role-specific dashboard |
| GET | `/projects` | Authenticated | Paginated project list scoped by role |
| GET | `/tasks` | Authenticated | Latest task board: all tasks (admin), owned-project milestones (client), assigned tasks (team/intern) |
| GET | `/messages` | Authenticated | Recent project conversation and composer, scoped to admin/owned/assigned projects |
| GET | `/payments` | Authenticated | Payment ledger scoped to admin/owned/assigned projects |
| GET | `/files` | Authenticated | Private project file library, scoped to admin/owned/assigned projects |
| POST | `/projects` | Admin | `client_id`, `name`, `description?`, `status`, `priority`, `budget?`, `due_date?`, `members[]?` |
| GET | `/projects/{project}` | Admin, owning client, or assigned member | Project detail with tasks, payments, messages, files |
| POST | `/projects/{project}/tasks` | Admin or assigned member | `title`, `assignee_id`, `status`, `priority`, `due_date?`; assignee must belong to project |
| PUT | `/tasks/{task}` | Admin or task assignee | `title`, `status`, `priority`, `due_date?` |
| POST | `/projects/{project}/payments` | Admin | `amount`, `currency`, `status`, `method?`, `reference?`, `due_date?` |
| POST | `/projects/{project}/messages` | Project participant | `body` (1–5000 characters) |
| POST | `/projects/{project}/files` | Project participant | Multipart `file`, max 10 MB; allowlisted document/image/archive types |
| GET | `/projects/{project}/files/{file}` | Project participant | Authorized attachment download |

## Validation and errors

Form validation returns the Inertia redirect with field errors. Unauthenticated requests redirect to login; unauthorized access returns 403; missing records return 404; throttled login returns 429. Laravel's standard exception handler formats these responses. In production, `APP_DEBUG=false` must hide stack traces.

## Roadmap for external integrations

There is no payment gateway, public token API, webhook, or API versioning in this MVP. Add these behind dedicated API resources, Sanctum tokens, idempotency keys, signed webhook verification, and versioned routes before exposing integrations.
