<?php

declare(strict_types=1);

namespace Fundrik\Toolbox\Tests;

use Fundrik\Toolbox\TypeCaster;
use Fundrik\Toolbox\TypeValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass( TypeValidator::class )]
#[UsesClass( TypeCaster::class )]
final class TypeValidatorTest extends FundrikTestCase {

	#[Test]
	public function it_checks_boolean_values_using_caster_rules(): void {

		$this->assertTrue( TypeValidator::is_bool( true ) );
		$this->assertTrue( TypeValidator::is_bool( false ) );
		$this->assertTrue( TypeValidator::is_bool( 0 ) );
		$this->assertTrue( TypeValidator::is_bool( '1' ) );
		$this->assertTrue( TypeValidator::is_bool( '0' ) );
		$this->assertFalse( TypeValidator::is_bool( 'true' ) );
		$this->assertFalse( TypeValidator::is_bool( 2 ) );
		$this->assertFalse( TypeValidator::is_bool( null ) );
	}

	#[Test]
	public function it_checks_integer_values_using_caster_rules(): void {

		$this->assertTrue( TypeValidator::is_int( -42 ) );
		$this->assertTrue( TypeValidator::is_int( '0042' ) );
		$this->assertTrue( TypeValidator::is_int( (string) PHP_INT_MAX ) );
		$this->assertFalse( TypeValidator::is_int( (string) PHP_INT_MAX . '0' ) );
		$this->assertFalse( TypeValidator::is_int( '-42' ) );
		$this->assertFalse( TypeValidator::is_int( 42.0 ) );
		$this->assertFalse( TypeValidator::is_int( null ) );
	}

	#[Test]
	public function it_checks_float_values_using_caster_rules(): void {

		$this->assertTrue( TypeValidator::is_float( -1.5 ) );
		$this->assertTrue( TypeValidator::is_float( -2 ) );
		$this->assertTrue( TypeValidator::is_float( '001.5' ) );
		$this->assertFalse( TypeValidator::is_float( '1e3' ) );
		$this->assertFalse( TypeValidator::is_float( '-1.5' ) );
		$this->assertFalse( TypeValidator::is_float( true ) );
		$this->assertFalse( TypeValidator::is_float( null ) );
	}

	#[Test]
	public function it_checks_string_values_using_caster_rules(): void {

		$this->assertTrue( TypeValidator::is_string( '' ) );
		$this->assertTrue( TypeValidator::is_string( ' text ' ) );
		$this->assertFalse( TypeValidator::is_string( 123 ) );
		$this->assertFalse( TypeValidator::is_string( null ) );
	}

	#[Test]
	public function it_checks_datetime_values_using_caster_rules(): void {

		$this->assertTrue( TypeValidator::is_datetime_immutable( '2026-03-04', 'Y-m-d' ) );
		$this->assertFalse( TypeValidator::is_datetime_immutable( '2026-02-30', 'Y-m-d' ) );
		$this->assertFalse( TypeValidator::is_datetime_immutable( '2026/03/04', 'Y-m-d' ) );
		$this->assertFalse( TypeValidator::is_datetime_immutable( 123, 'Y-m-d' ) );
		$this->assertFalse( TypeValidator::is_datetime_immutable( null, 'Y-m-d' ) );
	}

	#[Test]
	public function it_accepts_null_only_in_nullable_variants(): void {

		$this->assertTrue( TypeValidator::is_bool_nullable( null ) );
		$this->assertTrue( TypeValidator::is_int_nullable( null ) );
		$this->assertTrue( TypeValidator::is_float_nullable( null ) );
		$this->assertTrue( TypeValidator::is_string_nullable( null ) );
		$this->assertTrue( TypeValidator::is_datetime_immutable_nullable( null, 'Y-m-d' ) );
	}

	#[Test]
	public function it_applies_caster_rules_to_non_null_values_in_nullable_variants(): void {

		$this->assertTrue( TypeValidator::is_bool_nullable( '1' ) );
		$this->assertFalse( TypeValidator::is_bool_nullable( 'true' ) );
		$this->assertTrue( TypeValidator::is_int_nullable( '0042' ) );
		$this->assertFalse( TypeValidator::is_int_nullable( '-42' ) );
		$this->assertTrue( TypeValidator::is_float_nullable( '1.5' ) );
		$this->assertFalse( TypeValidator::is_float_nullable( '1e3' ) );
		$this->assertTrue( TypeValidator::is_string_nullable( '' ) );
		$this->assertFalse( TypeValidator::is_string_nullable( 42 ) );
		$this->assertTrue( TypeValidator::is_datetime_immutable_nullable( '2026-03-04', 'Y-m-d' ) );
		$this->assertFalse( TypeValidator::is_datetime_immutable_nullable( '2026-02-30', 'Y-m-d' ) );
	}
}
