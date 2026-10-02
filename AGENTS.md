# Testing Conventions

- If `phpunit` fails because `mbstring` is missing, run PHPUnit with `php -c .tmp/php.ini vendor/bin/phpunit`.
- Do not call private/protected methods via reflection in tests to force coverage.
- If target lines cannot be reached through the public API, report this explicitly and align on refactoring or coverage exclusions instead of testing internals directly.
- When tests need fake/dummy classes, create them in `tests/unit/Fixtures/` instead of declaring ad-hoc anonymous/helper classes inside test files.

# Exception Message Policy

- Keep exception message text developer-focused, concise, and in English.
- Use stable templates:
  - Validation: `<Field> must <constraint>. Given: <value>.`
  - Business rule: `Cannot <action> <entity> "<id>": <reason>.`
  - Infrastructure/read failures: `Failed to <action> <entity> "<id>".`
  - Post-action side-effect failures: `<Entity> "<id>" was <past participle>, but <side effect> failed.`
- End every exception message with a period.
- Do not build logic on `message` text.
- Use exception class as the primary discriminator.
- Add `stage`/`reason` only when there are 2+ meaningful failure branches that require different handling.
- Preserve low-level details via `previous` exceptions, not by concatenating nested messages into the top-level message.

# Editing Conventions

- When editing files in this repository, preserve Windows line endings (`CRLF`).
- Do not add runtime no-op code like `unset( $unused )` only to silence linting for fixed callback signatures; prefer a targeted `phpcs:ignore` with a short reason instead.

# Docblock Conventions

- Treat docblocks as concise API reference text, not prose paragraphs.
- Keep class/interface/enum/trait summary lines as one sentence in present tense, ending with a period.
- Prefer stable summary verbs by artifact role:
  - `Represents ...` for value objects, DTOs, commands, and read models.
  - `Provides ...` for services, factories, and ports when they expose an entry point or capability.
  - `Creates ...`, `Returns ...`, `Checks ...`, `Formats ...`, `Converts ...` for methods, based on what they do.
- For port interfaces, use the standard summary wording `Provides the <inbound|outbound> port for ...`.
- In `@param`, `@return`, and `@throws` descriptions, use short noun phrases or outcome phrases, not full explanatory sentences.
- Do not start `@param`, `@return`, or `@throws` descriptions with `The`; prefer `Campaign ID.` over `The campaign ID.`.
- Keep tag descriptions in sentence case and end them with a period.
- For booleans in `@return`, prefer `True when ...`.
- For nullable values, prefer explicit endings such as `..., if configured.` or `..., null otherwise.`.

# Project Scripts

- Source of truth for runnable project commands is the `scripts` section in `composer.json`.
- Before running lint/tests commands, read scripts from those files and execute via `composer run <script>`.
- In this workspace, if `composer run <script>` fails because Composer uses a PHP build without `openssl`, rerun it as `$env:COMPOSER_ALLOW_XDEBUG='1'; php -c .tmp/php.ini -d extension=php_openssl.dll C:\ProgramData\ComposerSetup\bin\composer.phar run <script>`.
- If a Composer script invokes `php` directly and still fails because extensions such as `mbstring` are missing, copy the underlying command from `composer.json` and run it with `php -c .tmp/php.ini ...` instead of plain `php`.
