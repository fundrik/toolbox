# Conversion Rules

Fundrik Toolbox accepts explicit input shapes and avoids PHP's broad implicit coercions. No method trims input or calls `__toString()`.

## Boolean

`TypeCaster::to_bool()` accepts only:

- `true` and `false`;
- integers `1` and `0`;
- strings `'1'` and `'0'`.

Values such as `'true'`, `'yes'`, `1.0`, and strings containing whitespace are rejected.

## Integer

`TypeCaster::to_int()` accepts any native integer. A string must contain one or more decimal digits, with no sign, decimal point, exponent, or whitespace. Leading zeroes are accepted. The normalized result must fit within the platform integer range.

Examples:

| Input | Result |
| --- | --- |
| `-42` | `-42` |
| `'0042'` | `42` |
| `'-42'` | Exception. |
| `'42.0'` | Exception. |
| `' 42 '` | Exception. |

## Float

`TypeCaster::to_float()` accepts native floats and integers. A string must contain decimal digits with an optional fractional part after a dot. Signs, exponent notation, surrounding whitespace, `.5`, and `5.` are rejected.

Native negative integers and floats are accepted; signed numeric strings are not.

## String

`TypeCaster::to_string()` accepts strings without changing their contents. Empty strings and surrounding whitespace are preserved. Numbers, booleans, stringable objects, and other values are rejected.

## Date and time

`TypeCaster::to_datetime_immutable()` requires a string and a format understood by `DateTimeImmutable::createFromFormat()`. Parser warnings and errors, including invalid calendar dates, cause an exception.

The returned value uses PHP's normal `createFromFormat()` defaults for fields that are not represented by the format. Include every field whose value must be deterministic, and include a timezone in the input format when the source carries one.

## Nullability

Nullable caster methods return `null` only when their input is exactly `null`. Every other input is passed to the corresponding non-nullable method.

For array extraction, optionality controls whether a key may be absent; nullability controls whether a present key may contain `null`. These are separate concerns. See [Public API](public-api.md#arrayextractor) for the behavior matrix.

## Error handling

Catch `InvalidArgumentException` around direct casts and `ArrayExtractionException` around array extraction:

```php
use Fundrik\Toolbox\ArrayExtractionException;
use Fundrik\Toolbox\ArrayExtractor;

try {
	$limit = ArrayExtractor::extract_int_required($input, 'limit');
} catch (ArrayExtractionException $exception) {
	// Translate the boundary failure for the calling application.
}
```

Exception messages describe the expected shape and the runtime type received. Do not use message text for program logic.
