<?php

declare(strict_types=1);

namespace Fundrik\Toolbox\Tests\Fixtures;

final class StringableObject {

	public function __toString(): string {

		return 'stringable-object';
	}
}
