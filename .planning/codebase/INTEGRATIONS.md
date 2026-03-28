# External Integrations

**Analysis Date:** 2026-03-28

## APIs & External Services

**PDF Generation:**
- DomPDF (barryvdh/laravel-dompdf) - PDF document generation
  - SDK/Client: Laravel facade `Barryvdh\DomPDF\Facade\Pdf`
  - Config: `config/dompdf.php`
  - Usage: Issue exports, logbook exports, monthly/yearly reports
  - Font storage: `storage/fonts/`

**Markdown Processing:**
- League CommonMark - Markdown to HTML conversion
  - SDK/Client: `League\CommonMark\MarkdownConverter`
  - Extensions: CommonMark Core, GitHub Flavored Markdown
  - Custom Blade directive: `@markdown()`
  - Service Provider: `app/Providers/MarkdownServiceProvider.php`

## Data Storage

**Databases:**
- SQLite (default development)
- MySQL (optional)
- PostgreSQL (optional)
- SQL Server (optional)
  - Connection: `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
  - Client: Eloquent ORM (Laravel)
  - Migrations: `database/migrations/`

**File Storage:**
- Local filesystem (default)
- S3 compatible storage (optional)
  - Config: `config/filesystems.php`
  - Environment: `FILESYSTEM_DISK`, `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_BUCKET`, `AWS_DEFAULT_REGION`

**Caching:**
- Database cache (default)
- Redis (optional)
  - Connection: `CACHE_STORE`, `REDIS_HOST`, `REDIS_PORT`, `REDIS_PASSWORD`
  - Client: `predis` or `phpredis`

## Authentication & Identity

**Auth Provider:**
- Laravel Breeze - Simple authentication scaffolding
  - Implementation: Session-based authentication with database-backed sessions
  - Controllers: `app/Http/Controllers/Auth/`
  - Features: Registration, login, password reset, email verification, profile management

**API Authentication:**
- Laravel Sanctum - API token authentication and SPA authentication
  - Config: `config/sanctum.php`
  - Stateful domains: Configured for local development (localhost, localhost:3000, 127.0.0.1:8000)
  - Guards: Uses 'web' guard for authentication
  - Token expiration: None (null = never expire)
  - Current usage: Available but not actively used for API routes (uses standard 'auth' middleware instead)

## Monitoring & Observability

**Error Tracking:**
- None configured (standard Laravel error handling)

**Logs:**
- Laravel Pail - Real-time log monitoring in development
  - Environment: `LOG_CHANNEL`, `LOG_STACK`, `LOG_LEVEL`
  - Channels: Stack, single file, daily files
  - Drivers: file, database, syslog, errorlog

**Activity Logging:**
- Custom activity logging via `App\Http\Controllers\Api\ActivityLogController`
- Database-backed activity tracking

## CI/CD & Deployment

**Hosting:**
- Not specified (standard Laravel deployment compatible)

**CI Pipeline:**
- None configured (standard development workflow)

## Environment Configuration

**Required env vars:**
- `APP_NAME` - Application name
- `APP_ENV` - Environment (local, production, etc.)
- `APP_KEY` - Application encryption key
- `APP_DEBUG` - Debug mode
- `APP_URL` - Application URL
- `DB_CONNECTION` - Database connection type
- `DB_DATABASE` - Database file or name
- `SESSION_DRIVER` - Session storage (database, file, redis, etc.)
- `CACHE_STORE` - Cache storage driver

**Optional env vars:**
- `MAIL_*` - SMTP configuration for emails
- `AWS_*` - S3 storage configuration
- `REDIS_*` - Redis configuration
- `VITE_APP_NAME` - Frontend app name

**Secrets location:**
- `.env` file (not committed to git)
- `.env.example` provides template

## Webhooks & Callbacks

**Incoming:**
- None configured

**Outgoing:**
- None configured

## External Service Integrations

**Font Loading (via DomPDF):**
- Google Fonts (fonts.googleapis.com, fonts.gstatic.com)
  - Purpose: Load external fonts in PDF generation
  - Config: `config/dompdf.php` allowed_remote_hosts

---

*Integration audit: 2026-03-28*
