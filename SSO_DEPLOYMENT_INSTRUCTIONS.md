# SSO Deployment Instructions

Use these environment variables in each app-specific `.env` file when hosting the three systems on different domains:

- `SSO_TOKEN_TTL` for token lifetime in minutes.
- `SSO_SECRET_KEY` for the shared signing secret.
- `SSO_LOGIN_URL` for the central event-system login page.
- `SSO_GROUP_URL_STAFF` and `SSO_GROUP_URL_STUDIO` for each portal entry URL.
- `SSO_LANDING_STAFF` and `SSO_LANDING_STUDIO` for each post-login landing page.

Logout should use the local portal routes, not `auth/logout`:

- Staff portal logout: `staff/logout`
- Studio portal logout: `studio/logout`

Keep the shared `SSO_SECRET_KEY` identical across all three systems so SSO tokens validate everywhere.