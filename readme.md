## Diceware

[![Build Status](https://travis-ci.org/tecnom1k3/diceware.svg?branch=features%2Fdevelop)](https://travis-ci.org/tecnom1k3/diceware)
[![Coverage Status](https://coveralls.io/repos/tecnom1k3/diceware/badge.svg)](https://coveralls.io/r/tecnom1k3/diceware)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tecnom1k3/diceware/badges/quality-score.png?b=features%2Fdevelop)](https://scrutinizer-ci.com/g/tecnom1k3/diceware/?branch=features%2Fdevelop)

## Install
- Create an `.env` file in root directory, use `.env.example` as a boilerplate.  in the file make sure you have the key `DB_CONNECTION=sqlite` and `DB_DATABASE=storage/app/database.sqlite`.
- Create a `database.sqlite`, you can copy/rename the file `storage/app/database.sqlite.dist` which already has the laravel migrations table installed.
- Run `composer install` to install dependencies
- Run `php artisan migrate` to create the persistence infraestructure

## Patched runtime and setup

Use PHP **8.3.36 or later within 8.3** by default. Also reviewed: PHP 8.2.35+,
8.4.27+, and 8.5.12+, within each listed minor branch. Run
`php scripts/verify-runtime.php` using the effective interpreter before Composer,
web startup, scheduled tasks, and workers. The application also checks at startup;
this cannot prevent exploitation before application startup.

Install with `composer install --no-interaction`, then run
`composer check-platform-reqs`, `composer check-platform-reqs --no-dev`, and
`composer audit --locked`. Do not bypass platform requirements or disable security
blocking. Preserve existing `.env` and databases; copy templates only for a fresh
setup. Use `CACHE_DRIVER=file`, `SESSION_DRIVER=file`, and
`QUEUE_CONNECTION=database` as in the updated template (older templates selected
Memcached and used `QUEUE_DRIVER`). Set SQLite configuration as described above
and run `php artisan migrate`.
Serve locally with `php -S localhost:8000 server.php`; `curl -f localhost:8000/`
must return `Hello World`.

Dependencies now use Lumen 11.2, Guzzle 7.16, Dotenv 5.7, Flysystem 3.36,
PHPUnit 10.5.66, Coveralls 2.9.1, and Behat 3. Run `vendor/bin/phpunit` and
`vendor/bin/behat`. Behat isolates storage/database and mocks the wordlist HTTP
response. PHPUnit coverage needs Xdebug or PCOV; CI retains Coveralls and
Scrutinizer uploads from `build/logs/clover.xml`.

## Security references

The PHP floors match [CVE-2026-109618](https://github.com/php/php-src/security/advisories/GHSA-w8hw-w9fx-j79w)
and [CVE-2026-109619](https://github.com/php/php-src/security/advisories/GHSA-6wmf-4rv5-p3rp).
Travis requests exact patched releases and fails before dependency installation
if the effective interpreter differs.

Lumen 11 uses Illuminate 11, whose published security support ended March 12,
2026 ([support policy](https://laravel.com/framework/docs/11.x/releases)). Retaining
Lumen avoids an architectural migration but leaves an ongoing maintenance risk.

See [the hotfix validation report](docs/php2shell-validation.md) for the exact
locked versions, changed files, and local validation results.
