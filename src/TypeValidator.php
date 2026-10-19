<?php

declare(strict_types=1);

namespace Fundrik\Toolbox;

use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Provides boolean checks for values accepted by TypeCaster.
 *
 * @since 1.1.0
 */
final readonly class TypeValidator {

	/**
	 * Checks whether the value can be converted to a boolean.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool True when TypeCaster accepts the value as a boolean.
	 */
	public static function is_bool( mixed $value ): bool {

		return self::accepts( $value, TypeCaster::to_bool( ... ) );
	}

	/**
	 * Checks whether the value can be converted to a nullable boolean.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool True when TypeCaster accepts the value as a nullable boolean.
	 */
	public static function is_bool_nullable( mixed $value ): bool {

		return self::accepts( $value, TypeCaster::to_bool_nullable( ... ) );
	}

	/**
	 * Checks whether the value can be converted to an integer.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool True when TypeCaster accepts the value as an integer.
	 */
	public static function is_int( mixed $value ): bool {

		return self::accepts( $value, TypeCaster::to_int( ... ) );
	}

	/**
	 * Checks whether the value can be converted to a nullable integer.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool True when TypeCaster accepts the value as a nullable integer.
	 */
	public static function is_int_nullable( mixed $value ): bool {

		return self::accepts( $value, TypeCaster::to_int_nullable( ... ) );
	}

	/**
	 * Checks whether the value can be converted to a float.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool True when TypeCaster accepts the value as a float.
	 */
	public static function is_float( mixed $value ): bool {

		return self::accepts( $value, TypeCaster::to_float( ... ) );
	}

	/**
	 * Checks whether the value can be converted to a nullable float.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool True when TypeCaster accepts the value as a nullable float.
	 */
	public static function is_float_nullable( mixed $value ): bool {

		return self::accepts( $value, TypeCaster::to_float_nullable( ... ) );
	}

	/**
	 * Checks whether the value is accepted as a string.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool True when TypeCaster accepts the value as a string.
	 */
	public static function is_string( mixed $value ): bool {

		return self::accepts( $value, TypeCaster::to_string( ... ) );
	}

	/**
	 * Checks whether the value is accepted as a nullable string.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool True when TypeCaster accepts the value as a nullable string.
	 */
	public static function is_string_nullable( mixed $value ): bool {

		return self::accepts( $value, TypeCaster::to_string_nullable( ... ) );
	}

	/**
	 * Checks whether the value can be converted to DateTimeImmutable.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 * @param string $format Expected datetime format.
	 *
	 * @return bool True when TypeCaster accepts the value as a datetime.
	 */
	public static function is_datetime_immutable( mixed $value, string $format ): bool {

		return self::accepts(
			$value,
			static fn ( mixed $input ): DateTimeImmutable => TypeCaster::to_datetime_immutable( $input, $format ),
		);
	}

	/**
	 * Checks whether the value can be converted to nullable DateTimeImmutable.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 * @param string $format Expected datetime format.
	 *
	 * @return bool True when TypeCaster accepts the value as a nullable datetime.
	 */
	public static function is_datetime_immutable_nullable( mixed $value, string $format ): bool {

		return self::accepts(
			$value,
			static fn ( mixed $input ): ?DateTimeImmutable => TypeCaster::to_datetime_immutable_nullable(
				$input,
				$format,
			),
		);
	}

	/**
	 * Checks whether a caster accepts the value.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $value Input value.
	 * @param callable $caster Caster to check.
	 *
	 * @return bool True when the caster accepts the value.
	 */
	private static function accepts( mixed $value, callable $caster ): bool {

		try {
			$caster( $value );
			return true;
		} catch ( InvalidArgumentException ) {
			return false;
		}
	}
}
