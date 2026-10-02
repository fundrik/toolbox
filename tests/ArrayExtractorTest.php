<?php

declare(strict_types=1);

namespace Fundrik\Toolbox\Tests;

use DateTimeImmutable;
use Fundrik\Toolbox\ArrayExtractionException;
use Fundrik\Toolbox\ArrayExtractor;
use Fundrik\Toolbox\TypeCaster;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass( ArrayExtractor::class )]
#[UsesClass( TypeCaster::class )]
final class ArrayExtractorTest extends FundrikTestCase {

	#[Test]
	public function it_extracts_bool_optional_correctly(): void {

		$this->assertTrue( ArrayExtractor::extract_bool_optional( [ 'flag' => true ], 'flag' ) );
		$this->assertFalse( ArrayExtractor::extract_bool_optional( [ 'flag' => false ], 'flag' ) );
		$this->assertTrue( ArrayExtractor::extract_bool_optional( [ 'flag' => '1' ], 'flag' ) );
		$this->assertFalse( ArrayExtractor::extract_bool_optional( [ 'flag' => 0 ], 'flag' ) );
		$this->assertNull( ArrayExtractor::extract_bool_optional( [], 'missing_flag' ) );
	}

	#[Test]
	public function it_throws_on_invalid_bool_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "flag" must be bool\. Given: .+\.$/' );

		ArrayExtractor::extract_bool_optional( [ 'flag' => 'maybe' ], 'flag' );
	}

	#[Test]
	public function it_extracts_int_optional_correctly(): void {

		$this->assertSame( 123, ArrayExtractor::extract_int_optional( [ 'num' => '123' ], 'num' ) );
		$this->assertSame( 42, ArrayExtractor::extract_int_optional( [ 'num' => 42 ], 'num' ) );
		$this->assertSame( 123, ArrayExtractor::extract_int_optional( [ 'num' => '00123' ], 'num' ) );
		$this->assertNull( ArrayExtractor::extract_int_optional( [], 'missing_num' ) );
	}

	#[Test]
	public function it_throws_on_invalid_int_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "num" must be int\. Given: .+\.$/' );

		ArrayExtractor::extract_int_optional( [ 'num' => 'not-an-int' ], 'num' );
	}

	#[Test]
	public function it_extracts_float_optional_correctly(): void {

		$this->assertSame( 123.45, ArrayExtractor::extract_float_optional( [ 'flt' => '123.45' ], 'flt' ) );
		$this->assertSame( 0.0, ArrayExtractor::extract_float_optional( [ 'flt' => 0 ], 'flt' ) );
		$this->assertSame( 99.0, ArrayExtractor::extract_float_optional( [ 'flt' => 99 ], 'flt' ) );
		$this->assertSame( 5.0, ArrayExtractor::extract_float_optional( [ 'flt' => '5.0' ], 'flt' ) );
		$this->assertNull( ArrayExtractor::extract_float_optional( [], 'missing_flt' ) );
	}

	#[Test]
	public function it_throws_on_invalid_float_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "flt" must be float\. Given: .+\.$/' );

		ArrayExtractor::extract_float_optional( [ 'flt' => 'not-a-float' ], 'flt' );
	}

	#[Test]
	public function it_extracts_string_optional_correctly(): void {

		$this->assertSame( 'text', ArrayExtractor::extract_string_optional( [ 'text' => 'text' ], 'text' ) );
		$this->assertSame( '  text  ', ArrayExtractor::extract_string_optional( [ 'text' => '  text  ' ], 'text' ) );
		$this->assertSame( '', ArrayExtractor::extract_string_optional( [ 'text' => '' ], 'text' ) );
		$this->assertNull( ArrayExtractor::extract_string_optional( [], 'missing_text' ) );
	}

	#[Test]
	public function it_throws_on_invalid_string_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "text" must be string\. Given: .+\.$/' );

		ArrayExtractor::extract_string_optional( [ 'text' => 123 ], 'text' );
	}

	#[Test]
	public function it_extracts_datetime_optional_correctly(): void {

		$date_time = ArrayExtractor::extract_datetime_optional(
			[ 'created_at' => '2026-03-04 08:09:10' ],
			'created_at',
			'Y-m-d H:i:s',
		);

		$this->assertInstanceOf( DateTimeImmutable::class, $date_time );
		$this->assertSame( '2026-03-04 08:09:10', $date_time->format( 'Y-m-d H:i:s' ) );
		$this->assertNull( ArrayExtractor::extract_datetime_optional( [], 'missing_created_at', 'Y-m-d H:i:s' ) );
	}

	#[Test]
	public function it_throws_on_invalid_datetime_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches(
			'/^Key "created_at" must be datetime string in format Y-m-d H:i:s\. Given: .+\.$/',
		);

		ArrayExtractor::extract_datetime_optional(
			[ 'created_at' => '2026-02-30 08:09:10' ],
			'created_at',
			'Y-m-d H:i:s',
		);
	}

	#[Test]
	public function it_extracts_array_optional_correctly(): void {

		$data = [
			'a' => 1,
			'b' => 2,
		];

		$this->assertSame( $data, ArrayExtractor::extract_array_optional( [ 'data' => $data ], 'data' ) );
		$this->assertNull( ArrayExtractor::extract_array_optional( [], 'missing_data' ) );
	}

	#[Test]
	public function it_throws_on_invalid_array_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "data" must be array\. Given: .+\.$/' );

		ArrayExtractor::extract_array_optional( [ 'data' => 'not-an-array' ], 'data' );
	}

	#[Test]
	public function it_describes_resource_when_array_optional_value_is_invalid(): void {

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$resource = fopen( 'php://memory', 'r' );

		try {
			$this->expectException( ArrayExtractionException::class );
			$this->expectExceptionMessage( 'Key "data" must be array. Given: resource.' );

			ArrayExtractor::extract_array_optional( [ 'data' => $resource ], 'data' );
		} finally {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
			fclose( $resource );
		}
	}

	#[Test]
	public function it_extracts_bool_required_correctly(): void {

		$this->assertTrue( ArrayExtractor::extract_bool_required( [ 'flag' => true ], 'flag' ) );
		$this->assertFalse( ArrayExtractor::extract_bool_required( [ 'flag' => false ], 'flag' ) );
		$this->assertTrue( ArrayExtractor::extract_bool_required( [ 'flag' => '1' ], 'flag' ) );
		$this->assertFalse( ArrayExtractor::extract_bool_required( [ 'flag' => 0 ], 'flag' ) );
	}

	#[Test]
	public function it_throws_on_invalid_bool_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "flag" must be bool\. Given: .+\.$/' );

		ArrayExtractor::extract_bool_required( [ 'flag' => 'yes' ], 'flag' );
	}

	#[Test]
	public function it_throws_on_missing_bool_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( 'Key "missing_flag" must be present. Given: missing.' );

		ArrayExtractor::extract_bool_required( [], 'missing_flag' );
	}

	#[Test]
	public function it_extracts_int_required_correctly(): void {

		$this->assertSame( 123, ArrayExtractor::extract_int_required( [ 'num' => '123' ], 'num' ) );
		$this->assertSame( 123, ArrayExtractor::extract_int_required( [ 'num' => '00123' ], 'num' ) );
		$this->assertSame( 456, ArrayExtractor::extract_int_required( [ 'num' => 456 ], 'num' ) );
	}

	#[Test]
	public function it_throws_on_invalid_int_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "num" must be int\. Given: .+\.$/' );

		ArrayExtractor::extract_int_required( [ 'num' => 5.99 ], 'num' );
	}

	#[Test]
	public function it_throws_on_missing_int_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( 'Key "missing_num" must be present. Given: missing.' );

		ArrayExtractor::extract_int_required( [], 'missing_num' );
	}

	#[Test]
	public function it_throws_on_non_numeric_string_int_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "num" must be int\. Given: .+\.$/' );

		ArrayExtractor::extract_int_required( [ 'num' => 'abc' ], 'num' );
	}

	#[Test]
	public function it_extracts_float_required_correctly(): void {

		$this->assertSame( 123.45, ArrayExtractor::extract_float_required( [ 'flt' => '123.45' ], 'flt' ) );
		$this->assertSame( 0.0, ArrayExtractor::extract_float_required( [ 'flt' => 0 ], 'flt' ) );
		$this->assertSame( 99.0, ArrayExtractor::extract_float_required( [ 'flt' => 99 ], 'flt' ) );
		$this->assertSame( 5.0, ArrayExtractor::extract_float_required( [ 'flt' => '5.0' ], 'flt' ) );
	}

	#[Test]
	public function it_throws_on_missing_float_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( 'Key "missing_flt" must be present. Given: missing.' );

		ArrayExtractor::extract_float_required( [], 'missing_flt' );
	}

	#[Test]
	public function it_throws_on_invalid_float_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "flt" must be float\. Given: .+\.$/' );

		ArrayExtractor::extract_float_required( [ 'flt' => 'not-a-float' ], 'flt' );
	}

	#[Test]
	public function it_throws_on_invalid_float_string_shapes(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "flt" must be float\. Given: .+\.$/' );

		ArrayExtractor::extract_float_required( [ 'flt' => ' 123.45' ], 'flt' );
	}

	#[Test]
	public function it_extracts_string_required_correctly(): void {

		$this->assertSame( 'text', ArrayExtractor::extract_string_required( [ 'text' => 'text' ], 'text' ) );
		$this->assertSame( '  text  ', ArrayExtractor::extract_string_required( [ 'text' => '  text  ' ], 'text' ) );
	}

	#[Test]
	public function it_throws_on_missing_string_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( 'Key "missing_text" must be present. Given: missing.' );

		ArrayExtractor::extract_string_required( [], 'missing_text' );
	}

	#[Test]
	public function it_throws_on_invalid_string_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "text" must be string\. Given: .+\.$/' );

		ArrayExtractor::extract_string_required( [ 'text' => 123 ], 'text' );
	}

	#[Test]
	public function it_extracts_datetime_required_correctly(): void {

		$date_time = ArrayExtractor::extract_datetime_required(
			[ 'created_at' => '2026-03-04' ],
			'created_at',
			'Y-m-d',
		);

		$this->assertInstanceOf( DateTimeImmutable::class, $date_time );
		$this->assertSame( '2026-03-04', $date_time->format( 'Y-m-d' ) );
	}

	#[Test]
	public function it_throws_on_missing_datetime_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( 'Key "missing_created_at" must be present. Given: missing.' );

		ArrayExtractor::extract_datetime_required( [], 'missing_created_at', 'Y-m-d' );
	}

	#[Test]
	public function it_throws_on_invalid_datetime_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches(
			'/^Key "created_at" must be datetime string in format Y-m-d\. Given: .+\.$/',
		);

		ArrayExtractor::extract_datetime_required( [ 'created_at' => '2026-02-30' ], 'created_at', 'Y-m-d' );
	}

	#[Test]
	public function it_extracts_array_required_correctly(): void {

		$data = [
			'x' => 10,
			'y' => 20,
		];

		$this->assertSame( $data, ArrayExtractor::extract_array_required( [ 'meta' => $data ], 'meta' ) );
	}

	#[Test]
	public function it_throws_on_missing_array_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( 'Key "missing_meta" must be present. Given: missing.' );

		ArrayExtractor::extract_array_required( [], 'missing_meta' );
	}

	#[Test]
	public function it_throws_on_invalid_array_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "meta" must be array\. Given: .+\.$/' );

		ArrayExtractor::extract_array_required( [ 'meta' => 'not-an-array' ], 'meta' );
	}

	#[Test]
	public function it_extracts_nullable_optional_values_correctly(): void {

		$this->assertTrue( ArrayExtractor::extract_bool_nullable_optional( [ 'flag' => '1' ], 'flag' ) );
		$this->assertSame( 123, ArrayExtractor::extract_int_nullable_optional( [ 'num' => '123' ], 'num' ) );
		$this->assertSame( 12.5, ArrayExtractor::extract_float_nullable_optional( [ 'flt' => '12.5' ], 'flt' ) );
		$this->assertSame( 'text', ArrayExtractor::extract_string_nullable_optional( [ 'text' => 'text' ], 'text' ) );
		$this->assertNull( ArrayExtractor::extract_datetime_nullable_optional( [ 'dt' => null ], 'dt', 'Y-m-d H:i:s' ) );

		$array = [
			'a' => 1,
		];

		$this->assertSame( $array, ArrayExtractor::extract_array_nullable_optional( [ 'arr' => $array ], 'arr' ) );
	}

	#[Test]
	public function it_extracts_nullable_required_values_correctly(): void {

		$this->assertTrue( ArrayExtractor::extract_bool_nullable_required( [ 'flag' => '1' ], 'flag' ) );
		$this->assertSame( 123, ArrayExtractor::extract_int_nullable_required( [ 'num' => '123' ], 'num' ) );
		$this->assertSame( 12.5, ArrayExtractor::extract_float_nullable_required( [ 'flt' => '12.5' ], 'flt' ) );
		$this->assertSame( 'text', ArrayExtractor::extract_string_nullable_required( [ 'text' => 'text' ], 'text' ) );

		$date_time = ArrayExtractor::extract_datetime_nullable_required(
			[ 'dt' => '2026-03-04 12:13:14' ],
			'dt',
			'Y-m-d H:i:s',
		);
		$this->assertInstanceOf( DateTimeImmutable::class, $date_time );
		$this->assertSame( '2026-03-04 12:13:14', $date_time->format( 'Y-m-d H:i:s' ) );
		$this->assertNull( ArrayExtractor::extract_datetime_nullable_required( [ 'dt' => null ], 'dt', 'Y-m-d H:i:s' ) );

		$array = [
			'a' => 1,
		];

		$this->assertSame( $array, ArrayExtractor::extract_array_nullable_required( [ 'arr' => $array ], 'arr' ) );
	}

	#[Test]
	#[DataProvider( 'nullable_optional_methods' )]
	public function nullable_optional_method_returns_null_for_missing_and_null( callable $method, string $key ): void {

		$this->assertNull( $method( [], $key ) );
		$this->assertNull( $method( [ $key => null ], $key ) );
	}

	public static function nullable_optional_methods(): array {

		return [
			[ ArrayExtractor::extract_bool_nullable_optional( ... ), 'flag' ],
			[ ArrayExtractor::extract_int_nullable_optional( ... ), 'num' ],
			[ ArrayExtractor::extract_float_nullable_optional( ... ), 'flt' ],
			[ ArrayExtractor::extract_string_nullable_optional( ... ), 'text' ],
			[
				static fn ( array $data, string $key ): ?DateTimeImmutable => ArrayExtractor::extract_datetime_nullable_optional(
					$data,
					$key,
					'Y-m-d H:i:s',
				),
				'dt',
			],
			[ ArrayExtractor::extract_array_nullable_optional( ... ), 'arr' ],
		];
	}

	#[Test]
	#[DataProvider( 'nullable_required_methods' )]
	public function nullable_required_method_returns_null_for_null( callable $method, string $key ): void {

		$this->assertNull( $method( [ $key => null ], $key ) );
	}

	#[Test]
	#[DataProvider( 'nullable_required_methods' )]
	public function nullable_required_method_throws_on_missing_key( callable $method, string $key ): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( sprintf( 'Key "%s" must be present. Given: missing.', $key ) );

		$method( [], $key );
	}

	public static function nullable_required_methods(): array {

		return [
			[ ArrayExtractor::extract_bool_nullable_required( ... ), 'flag' ],
			[ ArrayExtractor::extract_int_nullable_required( ... ), 'num' ],
			[ ArrayExtractor::extract_float_nullable_required( ... ), 'flt' ],
			[ ArrayExtractor::extract_string_nullable_required( ... ), 'text' ],
			[
				static fn ( array $data, string $key ): ?DateTimeImmutable => ArrayExtractor::extract_datetime_nullable_required(
					$data,
					$key,
					'Y-m-d H:i:s',
				),
				'dt',
			],
			[ ArrayExtractor::extract_array_nullable_required( ... ), 'arr' ],
		];
	}

	#[Test]
	#[DataProvider( 'nullable_optional_methods_with_invalid_values' )]
	public function nullable_optional_method_throws_on_invalid_value(
		callable $method,
		string $key,
		mixed $invalid_value,
		string $expected_message_pattern,
	): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( $expected_message_pattern );

		$method( [ $key => $invalid_value ], $key );
	}

	public static function nullable_optional_methods_with_invalid_values(): array {

		return [
			[
				ArrayExtractor::extract_bool_nullable_optional( ... ),
				'flag',
				'yes',
				'/^Key "flag" must be bool or null\. Given: .+\.$/',
			],
			[
				ArrayExtractor::extract_int_nullable_optional( ... ),
				'num',
				'12.5',
				'/^Key "num" must be int or null\. Given: .+\.$/',
			],
			[
				ArrayExtractor::extract_float_nullable_optional( ... ),
				'flt',
				'1e3',
				'/^Key "flt" must be float or null\. Given: .+\.$/',
			],
			[
				ArrayExtractor::extract_string_nullable_optional( ... ),
				'text',
				123,
				'/^Key "text" must be string or null\. Given: .+\.$/',
			],
			[
				static fn ( array $data, string $key ): ?DateTimeImmutable => ArrayExtractor::extract_datetime_nullable_optional(
					$data,
					$key,
					'Y-m-d H:i:s',
				),
				'dt',
				'invalid',
				'/^Key "dt" must be datetime string in format Y-m-d H:i:s or null\. Given: .+\.$/',
			],
			[
				ArrayExtractor::extract_array_nullable_optional( ... ),
				'arr',
				'not-array',
				'/^Key "arr" must be array or null\. Given: .+\.$/',
			],
		];
	}

	#[Test]
	#[DataProvider( 'nullable_required_methods_with_invalid_values' )]
	public function nullable_required_method_throws_on_invalid_value(
		callable $method,
		string $key,
		mixed $invalid_value,
		string $expected_message_pattern,
	): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( $expected_message_pattern );

		$method( [ $key => $invalid_value ], $key );
	}

	public static function nullable_required_methods_with_invalid_values(): array {

		return [
			[
				ArrayExtractor::extract_bool_nullable_required( ... ),
				'flag',
				'yes',
				'/^Key "flag" must be bool or null\. Given: .+\.$/',
			],
			[
				ArrayExtractor::extract_int_nullable_required( ... ),
				'num',
				'12.5',
				'/^Key "num" must be int or null\. Given: .+\.$/',
			],
			[
				ArrayExtractor::extract_float_nullable_required( ... ),
				'flt',
				'1e3',
				'/^Key "flt" must be float or null\. Given: .+\.$/',
			],
			[
				ArrayExtractor::extract_string_nullable_required( ... ),
				'text',
				123,
				'/^Key "text" must be string or null\. Given: .+\.$/',
			],
			[
				static fn ( array $data, string $key ): ?DateTimeImmutable => ArrayExtractor::extract_datetime_nullable_required(
					$data,
					$key,
					'Y-m-d H:i:s',
				),
				'dt',
				'invalid',
				'/^Key "dt" must be datetime string in format Y-m-d H:i:s or null\. Given: .+\.$/',
			],
			[
				ArrayExtractor::extract_array_nullable_required( ... ),
				'arr',
				'not-array',
				'/^Key "arr" must be array or null\. Given: .+\.$/',
			],
		];
	}

	#[Test]
	#[DataProvider( 'optional_methods_that_throw_on_null' )]
	public function optional_method_throws_on_null_value( callable $method, string $key ): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( '/^Key "[^"]+" must be .+\. Given: .+\.$/' );

		$method( [ $key => null ], $key );
	}

	public static function optional_methods_that_throw_on_null(): array {

		return [
			[ ArrayExtractor::extract_bool_optional( ... ), 'flag' ],
			[ ArrayExtractor::extract_int_optional( ... ), 'num' ],
			[ ArrayExtractor::extract_float_optional( ... ), 'flt' ],
			[ ArrayExtractor::extract_string_optional( ... ), 'text' ],
			[
				static fn ( array $data, string $key ): ?DateTimeImmutable => ArrayExtractor::extract_datetime_optional(
					$data,
					$key,
					'Y-m-d H:i:s',
				),
				'created_at',
			],
			[ ArrayExtractor::extract_array_optional( ... ), 'arr' ],
		];
	}
}
