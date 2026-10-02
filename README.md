# Fundrik Toolbox

*Strict, dependency-free PHP utilities for converting and extracting untrusted values.*

[![Checks](https://github.com/fundrik/toolbox/actions/workflows/checks.yml/badge.svg?branch=main)](https://github.com/fundrik/toolbox/actions/workflows/checks.yml?query=branch%3Amain)
![License](https://img.shields.io/github/license/fundrik/toolbox)
![Packagist](https://img.shields.io/packagist/v/fundrik/toolbox)
![PHP Version](https://img.shields.io/badge/PHP-8.3+-blue)
![Code Style](https://img.shields.io/badge/Code%20Style-FundrikStandard-blueviolet)

Fundrik Toolbox provides small utilities for handling values from configuration, decoded JSON, request payloads, database rows, and other weakly typed boundaries. It validates a deliberately narrow set of input shapes instead of relying on PHP's implicit coercion rules.

## Requirements

- PHP 8.3 or later.
- Composer 2.

## Installation

```bash
composer require fundrik/toolbox
```

## Capabilities

- Strictly convert raw values to `bool`, `int`, `float`, `string`, or `DateTimeImmutable`.
- Preserve `null` explicitly through nullable conversion methods.
- Extract required or optional typed values from associative arrays.
- Distinguish a missing key from a present `null` value.
- Report invalid array values through a dedicated exception while preserving the original exception.

## Quick start

Use `TypeCaster` when converting one value:

```php
use Fundrik\Toolbox\TypeCaster;

$enabled = TypeCaster::to_bool('1');
$limit = TypeCaster::to_int('25');
$started_at = TypeCaster::to_datetime_immutable(
	'2026-10-02 09:30:00',
	'Y-m-d H:i:s',
);
```

Use `ArrayExtractor` at an associative-array boundary:

```php
use Fundrik\Toolbox\ArrayExtractor;

$input = [
	'enabled' => '1',
	'limit' => '25',
	'label' => null,
];

$enabled = ArrayExtractor::extract_bool_required($input, 'enabled');
$limit = ArrayExtractor::extract_int_optional($input, 'limit') ?? 10;
$label = ArrayExtractor::extract_string_nullable_required($input, 'label');
```

The library does not trim strings or accept broad PHP truthiness and numeric-string syntax. For example, `'true'`, `' 1 '`, `'-10'` as an integer string, and `'1e3'` as a float string are rejected. See [Conversion rules](docs/conversion-rules.md) for the exact accepted shapes.

## Required, optional, and nullable

`ArrayExtractor` encodes two independent choices in each method name:

| Method suffix | Missing key | Present `null` |
| --- | --- | --- |
| `_required` | Throws `ArrayExtractionException` | Rejected unless the method is also `_nullable_` |
| `_optional` | Returns `null` | Rejected unless the method is also `_nullable_` |
| `_nullable_required` | Throws `ArrayExtractionException` | Returns `null` |
| `_nullable_optional` | Returns `null` | Returns `null` |

With an optional method, `null` can therefore mean that the key was absent. Use `array_key_exists()` separately if the distinction must be retained by the caller.

## Failures

`TypeCaster` throws `InvalidArgumentException` for unsupported values. `ArrayExtractor` throws `ArrayExtractionException` when a required key is missing or a present value is invalid. A casting failure is available as the exception's `previous` exception.

Branch on exception classes rather than message text. Messages are intended for developers and may change independently of the exception contract.

## Documentation

- [Public API](docs/public-api.md)
- [Conversion rules](docs/conversion-rules.md)
- [Examples](examples/README.md)
- [Changelog](CHANGELOG.md)

## Development

The Composer scripts are the source of truth for project checks:

Run `checks` for the regular local or pull request verification. It covers the checks required by the checks workflow:

```bash
composer run checks
```

Run `release-checks` before creating a release. It covers the checks required by the release-checks workflow, including random test ordering, coverage, and mutation testing:

```bash
composer run release-checks
```

For a focused subset, run `composer-checks` after changing `composer.json`, `composer.lock`, or Composer autoload configuration. It validates the Composer configuration, dependencies, and autoloading, and is already included in both aggregate workflows:

```bash
composer run composer-checks
```

Run individual tools when troubleshooting a specific failure:

```bash
composer run lint
composer run rector -- --dry-run
composer run phpstan
composer run test
composer run infection
```

## License

Fundrik Toolbox is released under the [MIT License](LICENSE).
