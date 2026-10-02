<?php

declare(strict_types=1);

namespace Fundrik\Toolbox;

use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Extracts typed values from associative arrays using strict validation.
 *
 * Provides helpers for retrieving values as specific types such as boolean,
 * integer, float, string, or array. Ensures that the extracted value matches
 * the expected type and throws an exception otherwise.
 *
 * Supports both optional and required extractions, returning null or throwing
 * if the key is missing, depending on the method.
 *
 * @since 1.0.0
 */
final readonly class ArrayExtractor {

	/**
	 * Extracts the boolean value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool|null The extracted boolean or null if the key is missing.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_bool_optional( array $data, string $key ): ?bool {

		return self::extract_value( $data, $key, TypeCaster::to_bool( ... ), 'bool', required: false );
	}

	/**
	 * Extracts the nullable boolean value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool|null The extracted boolean or null if the key is missing or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_bool_nullable_optional( array $data, string $key ): ?bool {

		return self::extract_value( $data, $key, TypeCaster::to_bool_nullable( ... ), 'bool or null', required: false );
	}

	/**
	 * Extracts the integer value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int|null The extracted integer or null if the key is missing.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_int_optional( array $data, string $key ): ?int {

		return self::extract_value( $data, $key, TypeCaster::to_int( ... ), 'int', required: false );
	}

	/**
	 * Extracts the nullable integer value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int|null The extracted integer or null if the key is missing or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_int_nullable_optional( array $data, string $key ): ?int {

		return self::extract_value( $data, $key, TypeCaster::to_int_nullable( ... ), 'int or null', required: false );
	}

	/**
	 * Extracts the float value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return float|null The extracted float or null if the key is missing.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_float_optional( array $data, string $key ): ?float {

		return self::extract_value( $data, $key, TypeCaster::to_float( ... ), 'float', required: false );
	}

	/**
	 * Extracts the nullable float value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return float|null The extracted float or null if the key is missing or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_float_nullable_optional( array $data, string $key ): ?float {

		// phpcs:ignore SlevomatCodingStandard.Files.LineLength.LineTooLong
		return self::extract_value( $data, $key, TypeCaster::to_float_nullable( ... ), 'float or null', required: false );
	}

	/**
	 * Extracts the string value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return string|null The extracted string or null if the key is missing.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_string_optional( array $data, string $key ): ?string {

		return self::extract_value( $data, $key, TypeCaster::to_string( ... ), 'string', required: false );
	}

	/**
	 * Extracts the nullable string value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return string|null The extracted string or null if the key is missing or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_string_nullable_optional( array $data, string $key ): ?string {

		return self::extract_value(
			$data,
			$key,
			TypeCaster::to_string_nullable( ... ),
			'string or null',
			required: false,
		);
	}

	/**
	 * Extracts the datetime value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 * @param string $format The expected datetime format.
	 *
	 * @return DateTimeImmutable|null The extracted datetime or null if the key is missing.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_datetime_optional( array $data, string $key, string $format ): ?DateTimeImmutable {

		return self::extract_value(
			$data,
			$key,
			static fn ( mixed $value ): DateTimeImmutable => TypeCaster::to_datetime_immutable( $value, $format ),
			sprintf( 'datetime string in format %s', $format ),
			required: false,
		);
	}

	/**
	 * Extracts the nullable datetime value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 * @param string $format The expected datetime format.
	 *
	 * @return DateTimeImmutable|null The extracted datetime or null if the key is missing or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_datetime_nullable_optional(
		array $data,
		string $key,
		string $format,
	): ?DateTimeImmutable {

		return self::extract_value(
			$data,
			$key,
			// phpcs:ignore SlevomatCodingStandard.Functions.RequireMultiLineCall.RequiredMultiLineCall, SlevomatCodingStandard.Files.LineLength.LineTooLong
			static fn ( mixed $value ): ?DateTimeImmutable => TypeCaster::to_datetime_immutable_nullable( $value, $format ),
			sprintf( 'datetime string in format %s or null', $format ),
			required: false,
		);
	}

	/**
	 * Extracts the array value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return array<mixed>|null The extracted array or null if the key is missing.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_array_optional( array $data, string $key ): ?array {

		return self::extract_value( $data, $key, self::assert_array( ... ), 'array', required: false );
	}

	/**
	 * Extracts the nullable array value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return array<mixed>|null The extracted array or null if the key is missing or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_array_nullable_optional( array $data, string $key ): ?array {

		return self::extract_value( $data, $key, self::assert_array_nullable( ... ), 'array or null', required: false );
	}

	/**
	 * Extracts the boolean value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool The extracted boolean.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_bool_required( array $data, string $key ): bool {

		return self::extract_value( $data, $key, TypeCaster::to_bool( ... ), 'bool' );
	}

	/**
	 * Extracts the nullable boolean value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool|null The extracted boolean or null.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_bool_nullable_required( array $data, string $key ): ?bool {

		return self::extract_value( $data, $key, TypeCaster::to_bool_nullable( ... ), 'bool or null' );
	}

	/**
	 * Extracts the integer value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int The extracted integer.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_int_required( array $data, string $key ): int {

		return self::extract_value( $data, $key, TypeCaster::to_int( ... ), 'int' );
	}

	/**
	 * Extracts the nullable integer value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int|null The extracted integer or null.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_int_nullable_required( array $data, string $key ): ?int {

		return self::extract_value( $data, $key, TypeCaster::to_int_nullable( ... ), 'int or null' );
	}

	/**
	 * Extracts the float value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return float The extracted float.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_float_required( array $data, string $key ): float {

		return self::extract_value( $data, $key, TypeCaster::to_float( ... ), 'float' );
	}

	/**
	 * Extracts the nullable float value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return float|null The extracted float or null.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_float_nullable_required( array $data, string $key ): ?float {

		return self::extract_value( $data, $key, TypeCaster::to_float_nullable( ... ), 'float or null' );
	}

	/**
	 * Extracts the string value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return string The extracted string.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_string_required( array $data, string $key ): string {

		return self::extract_value( $data, $key, TypeCaster::to_string( ... ), 'string' );
	}

	/**
	 * Extracts the nullable string value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return string|null The extracted string or null.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_string_nullable_required( array $data, string $key ): ?string {

		return self::extract_value( $data, $key, TypeCaster::to_string_nullable( ... ), 'string or null' );
	}

	/**
	 * Extracts the datetime value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 * @param string $format The expected datetime format.
	 *
	 * @return DateTimeImmutable The extracted datetime.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_datetime_required( array $data, string $key, string $format ): DateTimeImmutable {

		return self::extract_value(
			$data,
			$key,
			static fn ( mixed $value ): DateTimeImmutable => TypeCaster::to_datetime_immutable( $value, $format ),
			sprintf( 'datetime string in format %s', $format ),
		);
	}

	/**
	 * Extracts the nullable datetime value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 * @param string $format The expected datetime format.
	 *
	 * @return DateTimeImmutable|null The extracted datetime or null.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_datetime_nullable_required(
		array $data,
		string $key,
		string $format,
	): ?DateTimeImmutable {

		return self::extract_value(
			$data,
			$key,
			static fn ( mixed $value ): ?DateTimeImmutable => TypeCaster::to_datetime_immutable_nullable(
				$value,
				$format,
			),
			sprintf( 'datetime string in format %s or null', $format ),
		);
	}

	/**
	 * Extracts the array value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return array<mixed> The extracted array.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_array_required( array $data, string $key ): array {

		return self::extract_value( $data, $key, self::assert_array( ... ), 'array' );
	}

	/**
	 * Extracts the nullable array value for the given key from the source array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return array<mixed>|null The extracted array or null.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_array_nullable_required( array $data, string $key ): ?array {

		return self::extract_value( $data, $key, self::assert_array_nullable( ... ), 'array or null' );
	}

	// phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
	/**
	 * Extracts and casts the value for the given key using the provided caster.
	 *
	 * @since 1.0.0
	 *
	 * @template T
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to extract.
	 * @param callable $caster The function that validates and converts the input.
	 * @param string $type_description The expected type name for error reporting.
	 * @param bool $required Whether the key is required. Default: true.
	 *
	 * @phpstan-param callable(mixed): T $caster
	 *
	 * @phpstan-return ($required is true ? T : T|null)
	 *
	 * @return mixed The extracted and cast value, null if the key is missing and not required.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	private static function extract_value(
		array $data,
		string $key,
		callable $caster,
		string $type_description,
		bool $required = true,
	): mixed {

		if ( ! array_key_exists( $key, $data ) ) {

			if ( $required ) {
				throw new ArrayExtractionException(
					sprintf( 'Key "%s" must be present. Given: missing.', $key ),
				);
			}

			return null;
		}

		$value = $data[ $key ];

		try {
			return $caster( $value );
		} catch ( InvalidArgumentException $e ) {
			throw new ArrayExtractionException(
				sprintf(
					'Key "%s" must be %s. Given: %s.',
					$key,
					$type_description,
					self::describe_value( $value ),
				),
				previous: $e,
			);
		}
	}
	// phpcs:enable

	/**
	 * Validates that the value is an array.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return array<mixed> The validated array value.
	 *
	 * @throws InvalidArgumentException When the value is not an array.
	 */
	private static function assert_array( mixed $value ): array {

		if ( ! is_array( $value ) ) {
			throw new InvalidArgumentException(
				sprintf( 'Value must be array. Given: %s.', self::describe_value( $value ) ),
			);
		}

		return $value;
	}

	/**
	 * Validates that the value is a nullable array.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return array<mixed>|null The validated array value or null.
	 *
	 * @throws InvalidArgumentException When the value is not an array or null.
	 */
	private static function assert_array_nullable( mixed $value ): ?array {

		if ( $value === null ) {
			return null;
		}

		return self::assert_array( $value );
	}

	/**
	 * Formats a value for deterministic validation messages.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return string A concise representation suitable for exception messages.
	 */
	private static function describe_value( mixed $value ): string {

		if ( is_resource( $value ) ) {
			return 'resource';
		}

		return get_debug_type( $value );
	}
}
