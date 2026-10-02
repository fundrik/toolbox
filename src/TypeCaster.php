<?php

declare(strict_types=1);

namespace Fundrik\Toolbox;

use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Provides strict casting utilities for transforming raw values into expected scalar types.
 *
 * Avoids PHP's implicit coercions by accepting only explicitly supported input shapes
 * and throwing on everything else.
 *
 * @since 1.0.0
 */
final readonly class TypeCaster {

	/**
	 * Converts the input to a boolean.
	 *
	 * Accepts only:
	 * - bool
	 * - int 0/1
	 * - string '0'/'1'
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return bool The converted boolean.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to bool.
	 */
	public static function to_bool( mixed $value ): bool {

		return match ( true ) {
			is_bool( $value ) => $value,

			is_int( $value ) && $value === 0 => false,
			is_int( $value ) && $value === 1 => true,

			is_string( $value ) && $value === '0' => false,
			is_string( $value ) && $value === '1' => true,

			default => self::throw_invalid_cast_exception( 'bool', $value ),
		};
	}

	/**
	 * Converts the input to a nullable boolean.
	 *
	 * Accepts only:
	 * - null
	 * - any input accepted by {@see self::to_bool()}
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return bool|null The converted boolean or null.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to bool.
	 */
	public static function to_bool_nullable( mixed $value ): ?bool {

		return self::to_nullable( $value, self::to_bool( ... ) );
	}

	/**
	 * Converts the input to an integer.
	 *
	 * Accepts only:
	 * - int
	 * - a decimal digit string (e.g., '42')
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return int The converted integer.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to int.
	 */
	public static function to_int( mixed $value ): int {

		if ( is_int( $value ) ) {
			return $value;
		}

		if ( ! is_string( $value ) || ! ctype_digit( $value ) ) {
			self::throw_invalid_cast_exception( 'int', $value );
		}

		$normalized_value = ltrim( $value, '0' );
		$normalized_value = $normalized_value === '' ? '0' : $normalized_value;
		$converted_value = (int) $value;

		if ( (string) $converted_value !== $normalized_value ) {
			self::throw_invalid_cast_exception( 'int', $value );
		}

		return $converted_value;
	}

	/**
	 * Converts the input to a nullable integer.
	 *
	 * Accepts only:
	 * - null
	 * - any input accepted by {@see self::to_int()}
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return int|null The converted integer or null.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to int.
	 */
	public static function to_int_nullable( mixed $value ): ?int {

		return self::to_nullable( $value, self::to_int( ... ) );
	}

	/**
	 * Converts the input to a float.
	 *
	 * Accepts only:
	 * - float
	 * - int
	 * - a decimal string in dot notation (e.g., '0', '1.23')
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return float The converted float.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to float.
	 */
	public static function to_float( mixed $value ): float {

		if ( is_float( $value ) ) {
			return $value;
		}

		if ( is_int( $value ) ) {
			return (float) $value;
		}

		if ( ! is_string( $value ) ) {
			self::throw_invalid_cast_exception( 'float', $value );
		}

		if ( preg_match( '/^\d+(?:\.\d+)?$/', $value ) !== 1 ) {
			self::throw_invalid_cast_exception( 'float', $value );
		}

		return (float) $value;
	}

	/**
	 * Converts the input to a nullable float.
	 *
	 * Accepts only:
	 * - null
	 * - any input accepted by {@see self::to_float()}
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return float|null The converted float or null.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to float.
	 */
	public static function to_float_nullable( mixed $value ): ?float {

		return self::to_nullable( $value, self::to_float( ... ) );
	}

	/**
	 * Validates that the input is a string and returns it.
	 *
	 * Accepts only:
	 * - string
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return string The input string.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to string.
	 */
	public static function to_string( mixed $value ): string {

		if ( ! is_string( $value ) ) {
			self::throw_invalid_cast_exception( 'string', $value );
		}

		return $value;
	}

	/**
	 * Converts the input to a nullable string.
	 *
	 * Accepts only:
	 * - null
	 * - any input accepted by {@see self::to_string()}
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return string|null The converted string or null.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to string.
	 */
	public static function to_string_nullable( mixed $value ): ?string {

		return self::to_nullable( $value, self::to_string( ... ) );
	}

	// phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
	/**
	 * Converts the input to DateTimeImmutable using the given format.
	 *
	 * Accepts only:
	 * - string matching the provided format
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 * @param string $format The expected datetime format.
	 *
	 * @return DateTimeImmutable The converted datetime.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to DateTimeImmutable.
	 */
	public static function to_datetime_immutable( mixed $value, string $format ): DateTimeImmutable {

		if ( ! is_string( $value ) ) {
			throw new InvalidArgumentException(
				sprintf(
					'Value must be a valid datetime string in format "%s". Given: %s.',
					$format,
					self::describe_value( $value ),
				),
			);
		}

		$date_time = DateTimeImmutable::createFromFormat( $format, $value );
		$errors = DateTimeImmutable::getLastErrors();
		$has_errors = $errors !== false && ( $errors['warning_count'] > 0 || $errors['error_count'] > 0 );

		if ( $date_time instanceof DateTimeImmutable && ! $has_errors ) {
			return $date_time;
		}

		throw new InvalidArgumentException(
			sprintf(
				'Value must be a valid datetime string in format "%s". Given: %s.',
				$format,
				self::describe_value( $value ),
			),
		);
	}
	// phpcs:enable

	/**
	 * Converts the input to nullable DateTimeImmutable using the given format.
	 *
	 * Accepts only:
	 * - null
	 * - any input accepted by {@see self::to_datetime_immutable()}
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The input value.
	 * @param string $format The expected datetime format.
	 *
	 * @return DateTimeImmutable|null The converted datetime or null.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to DateTimeImmutable.
	 */
	public static function to_datetime_immutable_nullable( mixed $value, string $format ): ?DateTimeImmutable {

		return self::to_nullable(
			$value,
			static fn ( mixed $input ): DateTimeImmutable => self::to_datetime_immutable( $input, $format ),
		);
	}

	/**
	 * Converts nullable input using the provided caster.
	 *
	 * @since 1.0.0
	 *
	 * @template T
	 *
	 * @param mixed $value The input value.
	 * @param callable $caster The caster for non-null values.
	 *
	 * @phpstan-param callable(mixed): T $caster
	 *
	 * @phpstan-return T|null
	 *
	 * @return mixed The converted value or null.
	 */
	private static function to_nullable( mixed $value, callable $caster ): mixed {

		if ( $value === null ) {
			return null;
		}

		return $caster( $value );
	}

	/**
	 * Throws an exception for a failed type cast.
	 *
	 * @since 1.0.0
	 *
	 * @param string $target_type The target type to cast to (e.g., 'int', 'bool').
	 * @param mixed $value The input value that failed to cast.
	 *
	 * @throws InvalidArgumentException When the cast cannot be performed.
	 */
	private static function throw_invalid_cast_exception( string $target_type, mixed $value ): never {

		throw new InvalidArgumentException(
			sprintf(
				'Value must be %s. Given: %s.',
				$target_type,
				self::describe_value( $value ),
			),
		);
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
