<?php

declare(strict_types=1);

namespace Fundrik\Toolbox\Tests;

use Fundrik\Toolbox\TypeCaster;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

#[CoversClass( TypeCaster::class )]
final class TypeCasterTest extends FundrikTestCase {

	#[Test]
	#[DataProvider( 'provide_values_for_to_bool' )]
	public function it_casts_to_bool_or_throws(
		mixed $value,
		?bool $expected,
		bool $should_throw,
		?string $expected_source_type = null,
		?string $expected_target_type = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_source_type !== null && $expected_target_type !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/Cannot cast resource(?: \([^)]+\))? to bool\./';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage(
						self::format_invalid_cast_message( $expected_source_type, $expected_target_type ),
					);
				}
			}
		}

		$result = TypeCaster::to_bool( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_bool(): array {

		return [
			[ true, true, false ],
			[ false, false, false ],

			[ 1, true, false ],
			[ 0, false, false ],
			[ -1, null, true, 'int', 'bool' ],
			[ 2, null, true, 'int', 'bool' ],
			[ 200, null, true, 'int', 'bool' ],

			[ '1', true, false ],
			[ '0', false, false ],
			[ '00123', null, true, 'string', 'bool' ],
			[ 'true', null, true, 'string', 'bool' ],
			[ 'false', null, true, 'string', 'bool' ],
			[ 'yes', null, true, 'string', 'bool' ],
			[ 'no', null, true, 'string', 'bool' ],
			[ '', null, true, 'string', 'bool' ],
			[ ' 1 ', null, true, 'string', 'bool' ],

			[ 0.0, null, true, 'float', 'bool' ],
			[ 1.0, null, true, 'float', 'bool' ],
			[ -0.99, null, true, 'float', 'bool' ],

			[ null, null, true, 'null', 'bool' ],
			[ [], null, true, 'array', 'bool' ],
			[ [ 'some' => 'value' ], null, true, 'array', 'bool' ],

			[
				new class() {

					public function __toString(): string {

						return 'stringable-object';
					}
				},
				null,
				true,
				'class@anonymous',
				'bool',
			],
			[ new stdClass(), null, true, 'stdClass', 'bool' ],

			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'resource', 'bool' ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_int' )]
	public function it_casts_to_int_or_throws(
		mixed $value,
		?int $expected,
		bool $should_throw,
		?string $expected_source_type = null,
		?string $expected_target_type = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_source_type !== null && $expected_target_type !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/Cannot cast resource(?: \([^)]+\))? to int\./';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage(
						self::format_invalid_cast_message( $expected_source_type, $expected_target_type ),
					);
				}
			}
		}

		$result = TypeCaster::to_int( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_int(): array {

		return [
			[ 0, 0, false ],
			[ 456, 456, false ],

			[ '0', 0, false ],
			[ '123', 123, false ],
			[ '00123', 123, false ],

			[ true, null, true, 'bool', 'int' ],
			[ false, null, true, 'bool', 'int' ],
			[ 456.0, null, true, 'float', 'int' ],
			[ 5.99, null, true, 'float', 'int' ],

			[ '5.99', null, true, 'string', 'int' ],
			[ '5.0', null, true, 'string', 'int' ],
			[ 'abc', null, true, 'string', 'int' ],
			[ '', null, true, 'string', 'int' ],
			[ ' 42', null, true, 'string', 'int' ],
			[ '42 ', null, true, 'string', 'int' ],
			[ '+42', null, true, 'string', 'int' ],
			[ '-42', null, true, 'string', 'int' ],

			[ null, null, true, 'null', 'int' ],
			[ [], null, true, 'array', 'int' ],
			[ new stdClass(), null, true, 'stdClass', 'int' ],
			[
				new class() {

					public function __toString(): string {

						return '42';
					}
				},
				null,
				true,
				'class@anonymous',
				'int',
			],

			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'resource', 'int' ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_float' )]
	public function it_casts_to_float_or_throws(
		mixed $value,
		?float $expected,
		bool $should_throw,
		?string $expected_source_type = null,
		?string $expected_target_type = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_source_type !== null && $expected_target_type !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/Cannot cast resource(?: \([^)]+\))? to float\./';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage(
						self::format_invalid_cast_message( $expected_source_type, $expected_target_type ),
					);
				}
			}
		}

		$result = TypeCaster::to_float( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_float(): array {

		return [
			[ 0.0, 0.0, false ],
			[ 456.78, 456.78, false ],

			[ 0, 0.0, false ],
			[ 123, 123.0, false ],

			[ '0', 0.0, false ],
			[ '123', 123.0, false ],
			[ '5.99', 5.99, false ],
			[ '5.0', 5.0, false ],
			[ '00123', 123.0, false ],
			[ '00123.450', 123.45, false ],

			[ true, null, true, 'bool', 'float' ],
			[ false, null, true, 'bool', 'float' ],

			[ 'abc', null, true, 'string', 'float' ],
			[ '', null, true, 'string', 'float' ],
			[ ' 1.23', null, true, 'string', 'float' ],
			[ '1.23 ', null, true, 'string', 'float' ],
			[ '+1.23', null, true, 'string', 'float' ],
			[ '-1.23', null, true, 'string', 'float' ],
			[ '.5', null, true, 'string', 'float' ],
			[ '5.', null, true, 'string', 'float' ],
			[ '1e3', null, true, 'string', 'float' ],

			[ null, null, true, 'null', 'float' ],
			[ [], null, true, 'array', 'float' ],
			[ new stdClass(), null, true, 'stdClass', 'float' ],
			[
				new class() {

					public function __toString(): string {

						return '42.5';
					}
				},
				null,
				true,
				'class@anonymous',
				'float',
			],

			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'resource', 'float' ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_string' )]
	public function it_casts_to_string_or_throws(
		mixed $value,
		?string $expected,
		bool $should_throw,
		?string $expected_source_type = null,
		?string $expected_target_type = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_source_type !== null && $expected_target_type !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/Cannot cast resource(?: \([^)]+\))? to string\./';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage(
						self::format_invalid_cast_message( $expected_source_type, $expected_target_type ),
					);
				}
			}
		}

		$result = TypeCaster::to_string( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_string(): array {

		return [
			[ 'text', 'text', false ],
			[ '  text  ', '  text  ', false ],
			[ '', '', false ],

			[ 123, null, true, 'int', 'string' ],
			[ 5.7, null, true, 'float', 'string' ],
			[ true, null, true, 'bool', 'string' ],
			[ false, null, true, 'bool', 'string' ],
			[
				new class() {

					public function __toString(): string {

						return 'stringable-object';
					}
				},
				null,
				true,
				'class@anonymous',
				'string',
			],
			[ new stdClass(), null, true, 'stdClass', 'string' ],
			[ null, null, true, 'null', 'string' ],
			[ [], null, true, 'array', 'string' ],
			[ [ 'array' ], null, true, 'array', 'string' ],

			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'resource', 'string' ],
		];
	}

	private static function format_invalid_cast_message( string $source_type, string $target_type ): string {

		return sprintf( 'Cannot cast %s to %s.', $source_type, $target_type );
	}
}
