# Database schema reference

The canonical DDL is represented by `database/migrations/`; use `php artisan migrate` to create it. Column types below are logical types; Laravel migrations use platform-appropriate SQL types.

| Table | Key fields and constraints |
|---|---|
| users | id PK; name; unique email; password hash; role; timestamps |
| projects | id PK; client_id FK users; unique slug; status; priority; decimal(12,2) budget; nullable start_date/due_date; unsigned progress |
| project_user | id PK; project_id FK; user_id FK; unique pair |
| tasks | id PK; project_id FK; nullable assignee_id FK; created_by FK; status; priority; nullable due_date |
| payments | id PK; project_id FK; decimal(12,2) amount; 3-char currency; status; method/reference; paid_at/due_date |
| messages | id PK; project_id FK; user_id FK; body text |
| project_files | id PK; project_id FK; user_id FK; original_name; private path; MIME; byte size; project/time index |
| password_reset_tokens | email PK; token; nullable created_at |

Foreign-key deletion rules and migration ordering are in the migration source. Production rollbacks should be coordinated with data retention and backup policies.
