# Scalability plan

## Foundation

Keep Laravel controllers stateless beyond the session, scope queries with indexed foreign keys, paginate project lists, and keep attachments private. The current MVP has no tenant boundary, queue worker, cache dependency, or horizontal deployment guarantee.

## Growth stages

1. **Pilot:** managed MySQL backups, request/error monitoring, object storage for private uploads, transactional email, CI checks and audit events.
2. **Growing agency:** Redis for cache, rate limits and queue; async notifications/file scanning; indexes guided by query plans; database connection pooling and scheduled retention.
3. **Multi-agency SaaS:** add `organization_id` to tenant-owned rows, tenant-aware global scopes and authorization tests, subscription/billing domain, per-tenant quotas, S3 lifecycle policies and tenant export/delete tools.
4. **Higher traffic:** horizontal PHP workers behind a load balancer, shared Redis sessions/cache, queue workers, CDN for public assets only, read replicas for reporting and partition/archive strategies only after measurements.

Do not add replicas or partitions before measuring actual query latency. Tenant isolation and billing require explicit schema and threat-model work before selling this as a multi-tenant SaaS.
