# AgencyOS Product Requirements Document

## Product and goal

AgencyOS is a multi-role workspace for small digital agencies to coordinate client projects, delivery tasks, assignments, payment records, shared files, and project conversations. This MVP is a single-agency foundation; tenant isolation and billing are future work.

## Personas

- **Admin:** oversees client accounts, projects, assignments, payment records and delivery health.
- **Client:** checks progress and tasks for their own projects, exchanges updates and accesses shared documents.
- **Team member:** sees assigned projects, completes assigned tasks and collaborates with clients and agency staff.
- **Intern:** sees assigned work and collaborates within project membership, with no finance or account-management rights.

## MVP requirements

1. Authenticate by email/password with session rotation, logout, CSRF protection and throttling.
2. Route users to role-specific dashboards and scope records on the server.
3. Let admins create projects for client accounts and assign team/intern members.
4. Track project status, priority, budget, due date and calculated task completion.
5. Create tasks with a project-member assignee and allow admins/assignees to update task status.
6. Keep payment records visible to authorized project participants; only admins can create them.
7. Support project messages and private project file uploads/downloads.
8. Validate inputs and provide clear form errors and success feedback.

## Out of scope

Gateway billing, invoices and tax logic, email notifications, user invitations, password reset UI, 2FA, time tracking, public APIs, multi-tenant subscriptions, audit exports and malware scanning are not implemented. They are explicit production follow-ups.

## Key flows

See [User flows](USER-FLOWS.md), [role matrix](ROLE-MATRIX.md), and [API reference](API.md).

## MVP acceptance checks

- Four seeded roles can sign in and see the correct dashboard.
- Client and contributor reads are scoped to owned/assigned projects; direct unauthorized URLs fail.
- An admin can create a project and attach only valid client/member accounts.
- Task updates recalculate project progress.
- Authorized participants can message and exchange private files.
- Payment creation is admin-only and appears on the project page.
- Database migrations and production frontend build complete successfully.
