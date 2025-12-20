<?php

declare(strict_types=1);

namespace Fundrik\Toolbox;

use InvalidArgumentException;

/**
 * Provides strict casting utilities for transforming raw values into expected scalar types.
 *
 * Avoids PHP's implicit coercions by accepting only explicitly supported input shapes
 * and throwing on everything else.
 *
 * @since 0.1.0
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
	 * @since 0.1.0
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
	 * Converts the input to an integer.
	 *
	 * Accepts only:
	 * - int
	 * - a decimal digit string (e.g., '42')
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return int The converted integer.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to int.
	 */
	public static function to_int( mixed $value ): int {

		return match ( true ) {
			is_int( $value ) => $value,
			is_string( $value ) && ctype_digit( $value ) => (int) $value,
			default => self::throw_invalid_cast_exception( 'int', $value ),
		};
	}

	/**
	 * Converts the input to a float.
	 *
	 * Accepts only:
	 * - float
	 * - int
	 * - a decimal string in dot notation (e.g., '0', '1.23')
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return float The converted float.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to float.
	 */
	public static function to_float( mixed $value ): float {

		if ( is_bool( $value ) ) {
			self::throw_invalid_cast_exception( 'float', $value );
		}

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
	 * Validates that the input is a string and returns it.
	 *
	 * @since 0.1.0
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
	 * Throws an exception for a failed type cast.
	 *
	 * @since 0.1.0
	 *
	 * @param string $target_type The target type to cast to (e.g., 'int', 'bool').
	 * @param mixed $value The input value that failed to cast.
	 *
	 * @throws InvalidArgumentException When the cast cannot be performed.
	 */
	private static function throw_invalid_cast_exception( string $target_type, mixed $value ): never {

		throw new InvalidArgumentException(
			sprintf( 'Cannot cast %s to %s.', get_debug_type( $value ), $target_type ),
		);
	}
}
