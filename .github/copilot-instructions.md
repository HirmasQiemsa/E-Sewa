# Copilot Instructions for Sistem-Sewa

This document guides AI coding agents to be productive in the Sistem-Sewa codebase. It covers architecture, workflows, conventions, and integration points specific to this Laravel-based rental management system.

## Architecture Overview
- **Framework**: Laravel (PHP), using MVC pattern. Key directories:
  - `app/Models/`: Eloquent models for domain entities (e.g., `Admin`, `Checkout`, `Fasilitas`, `Jadwal`).
  - `app/Http/Controllers/`: Handles HTTP requests and business logic.
  - `app/Actions/`, `app/Traits/`: Custom logic and reusable code.
  - `resources/views/`: Blade templates for UI.
  - `routes/`: Route definitions (`web.php`, `api.php`, etc.).
  - `database/migrations/`, `database/seeders/`, `database/factories/`: Schema, seed data, and test data.
- **Frontend**: Blade templates, Tailwind CSS, Vite, and AdminLTE (see `public/lte/`).
- **Authentication**: Uses Laravel Fortify and Jetstream (see `config/fortify.php`, `config/jetstream.php`).

## Developer Workflows
- **Start Local Server**: `php artisan serve` (default port 8000).
- **Run Migrations**: `php artisan migrate` (see `database/migrations/`).
- **Seed Database**: `php artisan db:seed`.
- **Run Tests**: `php artisan test` or `vendor/bin/phpunit` (see `tests/`).
- **Build Frontend**: `npm run build` (uses Vite and Tailwind CSS).
- **Install Dependencies**: `composer install` (PHP), `npm install` (JS/CSS).

## Project-Specific Conventions
- **Model Naming**: Singular, capitalized (e.g., `Fasilitas`, `Jadwal`).
- **Blade Views**: Organized by feature in `resources/views/`. Use Blade components for reusable UI.
- **Custom Actions/Traits**: Business logic often extracted to `app/Actions/` and `app/Traits/`.
- **AdminLTE Integration**: Custom styles/scripts in `public/lte/` and referenced in Blade views.
- **Config Files**: All service and feature configs in `config/`.

## Integration Points
- **External Packages**: Laravel Fortify, Jetstream, AdminLTE, Tailwind CSS, Vite.
- **Database**: SQLite for local development (`database/database.sqlite`).
- **Authentication**: Fortify/Jetstream for login, registration, and user management.
- **Frontend Build**: Vite for asset bundling, Tailwind for styling.

## Examples
- **Add a new model**: Create in `app/Models/`, add migration in `database/migrations/`, update relevant controller in `app/Http/Controllers/`.
- **Add a route**: Edit `routes/web.php` or `routes/api.php`, point to controller method.
- **Update UI**: Edit Blade files in `resources/views/`, use Tailwind classes and AdminLTE components.

## References
- Main Laravel docs: https://laravel.com/docs
- AdminLTE docs: https://adminlte.io/docs
- Tailwind CSS: https://tailwindcss.com/docs
- Vite: https://vitejs.dev/guide/

---
If any section is unclear or missing project-specific patterns, please provide feedback for further refinement.
