<?php

declare(strict_types=1);

namespace Webstract\Route;

interface RouteProviderInterface
{
	/** @return RouteDefinition[] */
	public function provideRouteDefinitions(): array;
}
