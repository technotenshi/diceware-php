# PHP2Shell hotfix validation

Validated locally on 2026-10-22, on `hotfix/php2shell`.
The existing untracked `AGENTS.md` was preserved.

## Dependencies

The lockfile was regenerated using actual PHP 8.2.35 and Composer 2.10.3, with
platform validation and security blocking enabled (no platform override).

| Package | Locked version |
| --- | --- |
| laravel/lumen-framework | 11.2.0 |
| guzzlehttp/guzzle | 7.16.0 |
| vlucas/phpdotenv | 5.7.0 |
| league/flysystem | 3.36.0 |
| league/flysystem-local | 3.35.3 |
| phpunit/phpunit | 10.5.66 |
| php-coveralls/php-coveralls | 2.9.1 |
| behat/behat | 3.34.0 |

The local adapter has its own release numbering. Behat retains `~3.0`.

## Results

| Effective PHP CLI | Full / production platform checks | PHPUnit | Behat | HTTP smoke test |
| --- | --- | --- | --- | --- |
| 8.2.35 | Passed / passed | 8 tests, 37 assertions | 2 scenarios, 7 steps | 200, Hello World |
| 8.3.36 | Passed / passed | 8 tests, 37 assertions | 2 scenarios, 7 steps | 200, Hello World |
| 8.4.27 | Passed / passed | 8 tests, 37 assertions | 2 scenarios, 7 steps | 200, Hello World |
| 8.5.12 | Passed / passed | 8 tests, 37 assertions | 2 scenarios, 7 steps | 200, Hello World |

- `composer validate --strict` passed, with consistent lock metadata.
- Normal Composer update and a fresh locked installation in a disposable checkout
  succeeded without bypass flags.
- `composer audit --locked` found no known security advisories.
- Fresh CI setup scripts, migrations, and migration status passed. The template
  now uses file cache/sessions and `QUEUE_CONNECTION`, avoiding the old mandatory
  Memcached extension. Both suites also passed after fresh setup.
- PHP syntax, Travis YAML, PHPUnit 10.5 XML schema, shell syntax, and diff
  whitespace checks passed.
- CI matrix and effective-interpreter checks passed, including rejection of a
  mismatched selected version.
- Runtime policy tests reject the release immediately below each patch floor,
  accept each floor and the next patch, reject prereleases at the floor,
  and reject PHP 7, older PHP 8 branches, and unknown future branches.
- Behat runs real commands through the console kernel with disposable SQLite and
  storage, mocking only HTTP transport. It verifies migration status and exact
  saved wordlist contents. Failure tests verify HTTP and storage exceptions do
  not announce successful completion. The queue base class loads successfully.
- PHP 8.2.35 with PCOV 1.0.12 generated Clover and HTML reports at the existing
  paths. The initial run without a driver warned; the coverage-enabled rerun
  passed. Other branches ran PHPUnit with `--no-coverage`.
- Coveralls 2.9.1 generated a payload successfully in `--dry-run` mode with dummy
  Travis metadata. Its executable is now `vendor/bin/php-coveralls`.

## Changed files

- Dependencies: `composer.json`, `composer.lock`.
- Runtime / CI: `.env.example`, `scripts/verify-runtime.php`, `.travis.yml`,
  `build/ci/createEnvFile.sh`, `build/ci/createSqliteFile.sh`.
- Application: `bootstrap/app.php`, `config/filesystems.php`,
  `app/Http/routes.php`, `app/Exceptions/Handler.php`,
  `app/Console/Commands/ImportData.php`, `app/Jobs/Job.php`,
  `src/Importer/Diceware.php`.
- Tests: `phpunit.xml`, `tests/src/Importer/DicewareTest.php`,
  `tests/RuntimePolicyTest.php`, `tests/CommandTest.php`,
  `features/bootstrap/FeatureContext.php`, `features/support/console.php`,
  `features/migration.feature`.
- Documentation: `readme.md`, `.github/copilot-instructions.md`, this report.

## Validation limits

Hosted Travis execution and actual Coveralls and Scrutinizer uploads were not
part of local validation. Existing integrations are retained. Local build logs
are under `/tmp/diceware-hotfix/` and are not committed.

Lumen 11 retains Illuminate 11, whose published security support ended March 12,
2026 ([support policy](https://laravel.com/framework/docs/11.x/releases)). This
hotfix does not resolve that ongoing framework maintenance risk.
