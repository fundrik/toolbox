<?php

declare(strict_types=1);

namespace Fundrik\Toolbox;

/**
 * Provides checks for arrays with string keys and for arrays whose values are also strings.
 *
 * @since 1.1.0
 */
final readonly class ArrayValidator {

	/**
	 * Checks whether the value is an array with string keys.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @phpstan-assert-if-true array<string, mixed> $value
	 *
	 * @return bool True when the value is an array with string keys.
	 */
	public static function is_array_with_string_keys( mixed $value ): bool {

		if ( ! is_array( $value ) ) {
			return false;
		}

		foreach ( array_keys( $value ) as $key ) {

			if ( ! TypeValidator::is_string( $key ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Checks whether the value is an array with string keys and values.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @phpstan-assert-if-true array<string, string> $value
	 *
	 * @return bool True when the value is an array with string keys and values.
	 */
	public static function is_array_with_string_keys_and_values( mixed $value ): bool {

		if ( ! is_array( $value ) ) {
			return false;
		}

		foreach ( $value as $key => $item ) {

			if ( ! TypeValidator::is_string( $key ) || ! TypeValidator::is_string( $item ) ) {
				return false;
			}
		}

		return true;
	}
}
