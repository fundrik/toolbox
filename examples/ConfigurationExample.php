<?php

declare(strict_types=1);

namespace Fundrik\Toolbox\Examples;

use DateTimeImmutable;
use Fundrik\Toolbox\ArrayExtractor;

/**
 * Demonstrates strict extraction from a configuration array.
 *
 * @since 1.0.0
 */
final readonly class ConfigurationExample {

	/**
	 * Normalizes application configuration.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $input Raw configuration.
	 *
	 * @return array{
	 *     enabled: bool,
	 *     retry_count: int,
	 *     endpoint: string|null,
	 *     started_at: DateTimeImmutable|null,
	 *     metadata: array<mixed>
	 * } Normalized configuration.
	 */
	public static function normalize( array $input ): array {

		return [
			'enabled' => ArrayExtractor::extract_bool_required( $input, 'enabled' ),
			'retry_count' => ArrayExtractor::extract_int_optional( $input, 'retry_count' ) ?? 3,
			'endpoint' => ArrayExtractor::extract_string_nullable_required( $input, 'endpoint' ),
			'started_at' => ArrayExtractor::extract_datetime_optional( $input, 'started_at', 'Y-m-d H:i:s' ),
			'metadata' => ArrayExtractor::extract_array_optional( $input, 'metadata' ) ?? [],
		];
	}
}
