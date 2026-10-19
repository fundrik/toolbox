# Public API

Fundrik Toolbox exposes utilities. It has no runtime dependencies beyond PHP.

## TypeCaster

`Fundrik\Toolbox\TypeCaster` validates and converts individual raw values. Non-nullable methods throw `InvalidArgumentException` when the input is unsupported.

| Method | Result | Accepted input |
| --- | --- | --- |
| `to_bool($value)` | `bool` | Boolean, integer `0` or `1`, string `'0'` or `'1'`. |
| `to_int($value)` | `int` | Integer or unsigned decimal digit string within the platform integer range. |
| `to_float($value)` | `float` | Float, integer, or unsigned decimal string in dot notation. |
| `to_string($value)` | `string` | String. |
| `to_datetime_immutable($value, $format)` | `DateTimeImmutable` | Valid string accepted by `DateTimeImmutable::createFromFormat()` for the supplied format. |

Each method has a nullable counterpart named `to_*_nullable()`. It returns `null` for a `null` input and otherwise applies the same rules as the non-nullable method.

The methods are strict validators with limited normalization, not general-purpose PHP casts. See [Conversion rules](conversion-rules.md).

## TypeValidator

`Fundrik\Toolbox\TypeValidator` reports whether `TypeCaster` accepts a value. It exposes `is_bool()`, `is_int()`, `is_float()`, `is_string()`, and `is_datetime_immutable($value, $format)`. Each method has an `is_*_nullable()` counterpart that also accepts `null`.

These methods return `true` for values the corresponding caster can convert and `false` when it throws `InvalidArgumentException`. Their names refer to the library's accepted values, not PHP's native runtime types: `is_bool('1')` returns `true`.

## ArrayValidator

`Fundrik\Toolbox\ArrayValidator` checks array shapes without converting values:

| Method | Accepted input |
| --- | --- |
| `is_array_with_string_keys($value)` | Array whose keys are all strings; values may have any type. |
| `is_array_with_string_keys_and_values($value)` | Array whose keys and values are all strings. |

Both methods return `false` for non-arrays and accept an empty array. PHP converts numeric string keys such as `'0'` to integers, so those keys do not pass either check.

## ArrayExtractor

`Fundrik\Toolbox\ArrayExtractor` reads values from `array<string, mixed>` and delegates scalar and datetime validation to `TypeCaster`.

The supported type families are:

- `bool`;
- `int`;
- `float`;
- `string`;
- `datetime`, with a caller-supplied format;
- `array`.

Every family provides four variants:

| Variant | Missing key | Present `null` | Invalid non-null value |
| --- | --- | --- | --- |
| `extract_*_required()` | Exception | Exception | Exception |
| `extract_*_optional()` | `null` | Exception | Exception |
| `extract_*_nullable_required()` | Exception | `null` | Exception |
| `extract_*_nullable_optional()` | `null` | `null` | Exception |

Array values are validated directly because `TypeCaster` does not expose an array caster.

## ArrayExtractionException

`Fundrik\Toolbox\ArrayExtractionException` extends `InvalidArgumentException`. It is thrown when:

- a required key is absent; or
- a present value does not satisfy the selected extractor.

When value conversion fails, the underlying `InvalidArgumentException` is retained as the previous exception. Consumers should catch `ArrayExtractionException` at array boundaries and should not parse its message.

## Compatibility

The public methods of `TypeCaster`, `TypeValidator`, `ArrayValidator`, and `ArrayExtractor`, together with `ArrayExtractionException`, follow semantic versioning. Private implementation details and exception message wording are not compatibility guarantees.
