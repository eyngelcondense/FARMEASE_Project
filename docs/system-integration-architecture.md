# FarmEase — System Integration & Architecture Overview

## Purpose
This document summarizes the FarmEase project architecture and integration approach from an Advanced Systems Integration and Architecture perspective. It highlights applied course concepts, current integration patterns, system implications, and recommended improvements.

## High-level Summary
- Multiple domain web applications: `event-system/`, `staff-management/`, and `studio-management/`.
- PHP backend (CodeIgniter-style) with Composer-managed dependencies in `vendor/`.
- Canonical relational data store: `current_farmease.sql` (MySQL).
- Containerized runtime with `docker-compose.yml` and per-service Dockerfiles.
- Centralized authentication via documented SSO (`SSO_DEPLOYMENT_INSTRUCTIONS.md`).
- Shared filesystem for uploads (`public/uploads/`) and local logs (`writable/logs/`).
- In-application event handlers (`app/Events/`) for intra-service decoupling.

## Tech Stack (observed)
- Backend: PHP (CodeIgniter structure)
- Database: MySQL (single schema dump present)
- Packaging: Composer
- Containerization: Docker + docker-compose; per-service nginx configs
- Auth: Centralized SSO (deployment docs present)

## Logical Components
- Service Applications: `event-system`, `staff-management`, `studio-management` — each contains Controllers, Models, Views, and service-specific `vendor/` dependencies.
- Shared Database: Relational schema acts as the canonical source of truth.
- Auth Provider: Central SSO handling identity and session federation across apps.
- File Storage: Local `public/uploads/` and `writable/` directories (likely volume-mounted in containers).
- Intra-service Events: `app/Events/` used to decouple logic inside each PHP app.

## Integration Style
- Primary pattern: Shared-database integration with synchronous HTTP for user-facing APIs.
- Auth integration: Central SSO used by all services — a positive example of centralized identity.
- Eventing: Local in-app events exist, but no cross-service message broker detected.
- File integration: Shared filesystem (container volume) used for uploads and assets.

## Implications (architecture & integration concerns)
- Tight coupling: Shared DB and filesystem create strong coupling — schema changes impact multiple services.
- Scalability limits: Synchronous DB access and local uploads hinder horizontal scale and fault isolation.
- Reliability: Absence of an async message bus and retry/backpressure patterns increases failure blast radius.
- Observability: Local logs exist, but centralized logging/metrics/tracing are not present.
- Security: SSO centralizes identity, but the strength depends on standards used (OAuth2/OIDC recommended).

## Applied Course Concepts (how the project maps to learning objectives)
- Service Decomposition: Domain separation into multiple apps demonstrates bounded responsibilities.
- Containerization & Reproducible Deployments: Use of Docker and `docker-compose` shows environment parity and deployment hygiene.
- Centralized Identity & Federation: SSO aligns with identity management best practices taught in class.
- Dependency Management: Composer demonstrates controlled third-party dependency usage.
- Intra-service Decoupling: `app/Events/` shows event-driven design inside services.
- Anti-pattern awareness: The shared database illustrates the "shared-database" anti-pattern discussed in class and the trade-offs of tight coupling vs. ease of integration.

## Strengths
- Clear logical separation by domain (events, staff, studio).
- Containerization enables consistent local and staging environments.
- Adoption of centralized SSO for unified auth and easier RBAC.
- Composer-based dependency management and modular app structure.

## Gaps Relative to Course Best Practices
- Shared database prevents true service autonomy and complicates independent deploys.
- No cross-service message broker (RabbitMQ/Kafka) for event-driven integration.
- No documented API contracts (OpenAPI) or contract tests for inter-service communication.
- Local file storage limits scaling and resilience; object storage recommended.
- Lack of centralized logging, metrics, and distributed tracing.
- No explicit use of distributed transaction patterns (Saga) for multi-step operations.
