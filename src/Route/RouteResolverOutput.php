<?php

declare(strict_types=1);

namespace Webstract\Route;

final class RouteResolverOutput
{
	public function __construct(
		public readonly RouteHandleable $route,
		public readonly ?array $pathParams,
	) {}
}
