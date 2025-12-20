<?php

declare(strict_types=1);

namespace Fundrik\Toolbox;

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
 * @since 0.1.0
 */
final readonly class ArrayExtractor {

	/**
	 * Extracts the boolean value for the given key from the source array.
	 *
	 * @since 0.1.0
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
	 * Extracts the integer value for the given key from the source array.
	 *
	 * @since 0.1.0
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
	 * Extracts the float value for the given key from the source array.
	 *
	 * @since 0.1.0
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
	 * Extracts the string value for the given key from the source array.
	 *
	 * @since 0.1.0
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
	 * Extracts the array value for the given key from the source array.
	 *
	 * @since 0.1.0
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
	 * Extracts the boolean value for the given key from the source array.
	 *
	 * @since 0.1.0
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
	 * Extracts the integer value for the given key from the source array.
	 *
	 * @since 0.1.0
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
	 * Extracts the float value for the given key from the source array.
	 *
	 * @since 0.1.0
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
	 * Extracts the string value for the given key from the source array.
	 *
	 * @since 0.1.0
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
	 * Extracts the array value for the given key from the source array.
	 *
	 * @since 0.1.0
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
	 * Extracts and casts the value for the given key using the provided caster.
	 *
	 * @since 0.1.0
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
				throw new ArrayExtractionException( sprintf( "Missing required key '%s'.", $key ) );
			}

			return null;
		}

		try {
			return $caster( $data[ $key ] );
		} catch ( InvalidArgumentException $e ) {
			throw new ArrayExtractionException(
				sprintf(
					"Invalid value at key '%s' (expected %s): %s",
					$key,
					$type_description,
					$e->getMessage(),
				),
				previous: $e,
			);
		}
	}

	/**
	 * Validates that the value is an array.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return array<mixed> The validated array value.
	 *
	 * @throws InvalidArgumentException When the value is not an array.
	 */
	private static function assert_array( mixed $value ): array {

		if ( ! is_array( $value ) ) {
			throw new InvalidArgumentException( sprintf( '%s given', get_debug_type( $value ) ) );
		}

		return $value;
	}
}
