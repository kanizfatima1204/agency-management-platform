# User flows

## Sign in and dashboard

1. User opens `/login`, submits email/password, and passes the five-per-minute throttle.
2. Laravel verifies the password, regenerates the session ID, then redirects to `/dashboard`.
3. Admin lands on agency-wide metrics; client sees owned projects; team/intern see assigned work.
4. Logout invalidates the session and rotates the CSRF token.

## Admin project delivery

1. Admin opens Projects and creates a project for an existing client.
2. Admin chooses eligible team members/interns; the server validates their roles and persists membership.
3. Admin or a project contributor creates tasks for project members.
4. The assignee updates task status; project progress is recomputed from completed tasks.
5. Admin records payment status. Project participants exchange messages and upload/download documents.

## Client collaboration

1. Client signs in and sees only projects where they are the client owner.
2. Client opens a project to view delivery progress, task list, payment trail, files and project messages.
3. Client posts a message or shares a file; the server verifies project ownership on every request.

## Contributor work

1. Team member or intern signs in and receives only assigned projects and own tasks.
2. A contributor opens an assigned project, updates their own task, communicates and exchanges project files.
3. Finance mutations and access to unassigned projects are denied server-side.
