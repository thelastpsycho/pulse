# Technology Stack

**Analysis Date:** 2026-03-28

## Languages

**Primary:**
- PHP ^8.2 - Backend application logic and Laravel framework

**Secondary:**
- JavaScript (ES6+) - Frontend interactivity via Alpine.js

## Runtime

**Environment:**
- PHP ^8.2
- Composer 2.x for PHP package management

**Package Manager:**
- Composer (PHP)
- npm (Node.js)
- Lockfile: `composer.lock` present, `package-lock.json` present

## Frameworks

**Core:**
- Laravel 12.53.0 - Full-stack web application framework
- Livewire v4.2 - Full-stack framework for dynamic UIs

**Testing:**
- PHPUnit ^11.5.3 - PHP testing framework
- Faker ^1.23 - Test data generation
- Mockery ^1.6 - Mocking framework

**Build/Dev:**
- Vite ^7.0.7 - Frontend build tool and dev server
- Laravel Pint ^1.24 - PHP code style fixer
- Laravel Breeze ^2.3 - Authentication scaffolding
- Laravel Sail ^1.41 - Docker development environment
- Laravel Tinker ^2.10.1 - REPL for Laravel

**Frontend:**
- Alpine.js ^3.15.8 - Lightweight JavaScript framework for interactivity
- Tailwind CSS ^3.1.0 - Utility-first CSS framework
- Tailwind Typography ^0.5.19 - Typography plugin
- Tailwind Forms ^0.5.11 - Form styling plugin

## Key Dependencies

**Critical:**
- laravel/sanctum ^4.3 - API authentication and tokens
- barryvdh/laravel-dompdf ^3.1 - PDF generation from Blade views
- league/commonmark ^2.8 - Markdown parsing with GitHub Flavored Markdown support
- livewire/livewire ^4.2 - Dynamic UI components without building a separate API

**Infrastructure:**
- laravel/pail ^1.2.2 - Log monitoring
- laravel/framework ^12.0 - Core Laravel framework

**Development:**
- concurrently ^9.0.1 - Run multiple npm scripts simultaneously
- laravel-vite-plugin ^2.0.0 - Vite integration with Laravel
- axios ^1.11.0 - HTTP client for AJAX requests

## Configuration

**Environment:**
- Environment variables via `.env` file
- Key configs: `config/app.php`, `config/database.php`, `config/sanctum.php`, `config/dompdf.php`, `config/livewire.php`
- Example config: `.env.example`

**Build:**
- `vite.config.js` - Vite configuration with Laravel plugin
- `tailwind.config.js` - Tailwind CSS customization with custom color palette
- `phpunit.xml` - PHPUnit configuration with SQLite in-memory database for testing
- `composer.json` - PHP dependencies and autoloading
- `package.json` - Node dependencies and build scripts

## Platform Requirements

**Development:**
- PHP ^8.2
- Composer 2.x
- Node.js + npm
- SQLite (default) or MySQL/PostgreSQL

**Production:**
- PHP ^8.2
- Composer dependencies installed
- Node.js + npm for asset building
- Database: SQLite, MySQL, PostgreSQL, or SQL Server

---

*Stack analysis: 2026-03-28*
