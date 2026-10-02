# Examples

The examples demonstrate the supported API without selecting a framework or input source.

- [`ConfigurationExample`](ConfigurationExample.php) maps a weakly typed configuration array to a documented array shape.

## Running the example

From the repository root, install development dependencies and regenerate the Composer autoloader:

```bash
composer install
```

Then use the example class from a local script or an interactive shell:

```php
use Fundrik\Toolbox\Examples\ConfigurationExample;

$configuration = ConfigurationExample::normalize([
	'enabled' => '1',
	'retry_count' => '3',
	'endpoint' => null,
	'started_at' => '2026-10-02 09:30:00',
]);
```

The example intentionally lets `ArrayExtractionException` propagate. An application should catch that exception at its input boundary and translate it into the failure appropriate for that boundary.

See [Public API](../docs/public-api.md) for the complete method families and [Conversion rules](../docs/conversion-rules.md) for accepted inputs.
