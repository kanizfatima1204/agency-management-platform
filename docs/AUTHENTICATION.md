# Authentication and authorization

## Authentication architecture

The MVP uses Laravel's first-party session guard and `users` table. Login validates credentials through `Auth::attempt`, regenerates the session ID, and redirects to a dashboard. Logout logs out, invalidates the session, and regenerates the CSRF token. The Inertia middleware shares only id, name, email and role. The seeded local demo accounts are not a production identity workflow.

## Authorization architecture

The `role` middleware handles route-level admin gates, while controllers check project owner/member relationships and task assignee identity for resource-level decisions. Lists are scoped before pagination. The role matrix documents each capability. Do not trust client-supplied role, ownership, creator, or project IDs; derive them from authenticated user and route-bound records.

The current role field is a single role per user. Replace with a policy/permission package or organization-scoped membership model if users need multiple organizations or custom roles. Add automated authorization coverage before expanding the permission surface.
