<?php

declare(strict_types=1);

namespace Fundrik\Toolbox\Tests;

use Fundrik\Toolbox\ArrayValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass( ArrayValidator::class )]
final class ArrayValidatorTest extends FundrikTestCase {

	#[Test]
	public function it_accepts_arrays_with_only_string_keys(): void {

		$value = [
			'name' => 123,
			'enabled' => null,
		];

		$this->assertTrue( ArrayValidator::is_array_with_string_keys( [] ) );
		$this->assertTrue( ArrayValidator::is_array_with_string_keys( $value ) );
		$this->assertTrue( ArrayValidator::is_array_with_string_keys( [ '01' => 'value' ] ) );
	}

	#[Test]
	public function it_rejects_non_arrays_and_integer_keys(): void {

		$value = [
			'name' => 'value',
			0 => 'item',
		];

		$this->assertFalse( ArrayValidator::is_array_with_string_keys( null ) );
		$this->assertFalse( ArrayValidator::is_array_with_string_keys( 'name' ) );
		$this->assertFalse( ArrayValidator::is_array_with_string_keys( [ 'name', 'value' ] ) );
		$this->assertFalse( ArrayValidator::is_array_with_string_keys( $value ) );
		$this->assertFalse( ArrayValidator::is_array_with_string_keys( [ '0' => 'value' ] ) );
	}

	#[Test]
	public function it_accepts_arrays_with_string_keys_and_values(): void {

		$value = [
			'name' => '',
			'01' => 'value',
		];

		$this->assertTrue( ArrayValidator::is_array_with_string_keys_and_values( [] ) );
		$this->assertTrue( ArrayValidator::is_array_with_string_keys_and_values( $value ) );
	}

	#[Test]
	public function it_rejects_non_arrays_and_non_string_keys_or_values(): void {

		$value = [
			'name' => 'value',
			0 => 123,
		];

		$this->assertFalse( ArrayValidator::is_array_with_string_keys_and_values( null ) );
		$this->assertFalse( ArrayValidator::is_array_with_string_keys_and_values( 'value' ) );
		$this->assertFalse( ArrayValidator::is_array_with_string_keys_and_values( [ 'name' => 123 ] ) );
		$this->assertFalse( ArrayValidator::is_array_with_string_keys_and_values( [ 'name' => null ] ) );
		$this->assertFalse( ArrayValidator::is_array_with_string_keys_and_values( [ 0 => 'value' ] ) );
		$this->assertFalse( ArrayValidator::is_array_with_string_keys_and_values( $value ) );
	}
}
