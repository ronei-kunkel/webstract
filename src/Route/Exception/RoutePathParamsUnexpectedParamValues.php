<?php

declare(strict_types=1);

namespace Webstract\Route\Exception;

use RuntimeException;

final class RoutePathParamsUnexpectedParamValues extends RuntimeException
{
	public function __construct(string $format, int $numberOfPlaceholders, int $numberOfParams)
	{
		parent::__construct("Route format `$format` has unfilled placeholders. Expected `$numberOfPlaceholders` but received `$numberOfParams`");
	}
}
