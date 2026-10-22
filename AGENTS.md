# Repository Guidelines

## Project Structure & Module Organization

This legacy PHP application uses Laravel Lumen 5.0. Custom wordlist import logic lives in `src/Importer/` under the `Acme\` namespace; framework application code lives in `app/` under `App\`. Routes are defined in `app/Http/routes.php`, and console commands in `app/Console/Commands/`. Database migrations live in `database/migrations/`. `public/` contains the web entry point, while `storage/` holds runtime files and the SQLite database. PHPUnit tests live in `tests/`; Behat scenarios and step definitions live in `features/`. CI setup scripts are in `build/ci/`.

## Build, Test, and Development Commands

- `composer install`: install dependencies from `composer.lock`. Existing setup notes prescribe `composer install --ignore-platform-reqs --no-interaction` on modern PHP; bypassing constraints does not resolve runtime incompatibilities.
- `cp .env.example .env`: create local configuration. Set `DB_CONNECTION=sqlite` and `DB_DATABASE=storage/app/database.sqlite`.
- `cp storage/app/database.sqlite.dist storage/app/database.sqlite`: initialize a fresh local database; preserve any existing database.
- `php -S localhost:8000 server.php`: start the development server.
- `curl -f http://localhost:8000/`: smoke-test the root route; expect `Hello World`.

There is no separate asset build step. Read `.github/copilot-instructions.md` for documented compatibility limitations before using console commands.

## Coding Style & Naming Conventions

Follow surrounding PHP conventions, preferring four-space indentation, PascalCase class names, camelCase methods and properties, and uppercase underscore-separated constants. Match class filenames to their names and preserve namespace-to-directory mappings. Avoid unrelated formatting changes in older files with tabs. No dedicated formatter or lint configuration is checked in.

## Testing Guidelines

The legacy CI configuration runs `./vendor/bin/phpunit` and `./vendor/bin/behat` on PHP 5.4–5.6. Existing notes report failures on PHP 8.3; use manual web validation there and document validation gaps. Name PHPUnit classes `*Test` and methods `test*`; mirror importer paths under `tests/src/`. Mock HTTP and filesystem dependencies. Coverage output is configured under `build/logs/`; no minimum percentage is specified.

## Commit & Pull Request Guidelines

Recent commits use short descriptive subjects, often beginning with “Add”; merged changes may include a PR number. No strict commit prefix convention is evident. Keep changes focused. Describe the behavior changed, link relevant issues, and report validation results and runtime limitations. Include screenshots for visible UI changes. Keep local `.env` files, credentials, generated databases, and dependency directories out of commits.
