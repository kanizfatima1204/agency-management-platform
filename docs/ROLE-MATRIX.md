# Role and permission matrix

Authorization is enforced server-side. Hiding controls in Vue is only a usability choice, never an access boundary.

| Capability | Admin | Client | Team member | Intern |
|---|---:|---:|---:|---:|
| Role-specific dashboard | Yes | Yes | Yes | Yes |
| List projects | All | Own client projects | Assigned projects | Assigned projects |
| Open task board | All tasks | Tasks in own projects | Own assigned tasks | Own assigned tasks |
| Read/post project messages | Any project | Own project | Assigned project | Assigned project |
| View payment ledger | All | Own projects | Assigned projects | Assigned projects |
| Browse/download shared files | All | Own projects | Assigned projects | Assigned projects |
| View project details, payments, files and messages | All | Own projects | Assigned projects | Assigned projects |
| Create projects / assign team | Yes | No | No | No |
| Create task | Any project | No | Assigned project | Assigned project |
| Update task | Any task | No | Own assigned task | Own assigned task |
| Record payment | Yes | No | No | No |
| Post project message | Any project | Own project | Assigned project | Assigned project |
| Upload/download project files | Any project | Own project | Assigned project | Assigned project |
| Invite users, manage roles, global settings | No MVP screen | No | No | No |

Interns have no separate resource model or extra destructive permission; they operate as restricted project contributors. Account provisioning and role assignment are currently seed/admin-operational tasks. Add a dedicated user management workflow before production onboarding.
