# Diceware PHP application

This application retains Lumen and now requires patched PHP. Use PHP 8.3.36 or a
later 8.3 patch for default development and deployment. Other reviewed branches
are PHP 8.2.35+, 8.4.27+, and 8.5.12+; future minor branches need review.

Read `readme.md` for installation, testing, runtime verification, and operator
remediation steps. Run `php scripts/verify-runtime.php` before installing with
`composer install --no-interaction`. Keep platform checks and security blocking
enabled; do not bypass platform requirements.

Console commands and automated tests are supported after the Lumen 11 upgrade.
Run `vendor/bin/phpunit` and `vendor/bin/behat`. Behat uses disposable storage,
a disposable SQLite database, and a mocked HTTP response. Preserve existing
`.env`, databases, imported wordlists, and the untracked `AGENTS.md`.

Application code lives in `app/`, importer code in `src/Importer/`, routes in
`app/Http/routes.php`, migrations in `database/migrations/`, and tests in `tests/`
and `features/`. The root web route must return `Hello World`.
