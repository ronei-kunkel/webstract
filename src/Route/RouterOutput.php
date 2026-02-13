<?php

declare(strict_types=1);

namespace Webstract\Route;

final class RouterOutput
{
	public function __construct(
		public readonly RouteHandleable $route,
		public readonly ?array $pathParams,
	) {}
}
