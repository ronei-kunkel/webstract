<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Route\RouteDefinition;
use Webstract\Route\RouteProvider;

final class FakeRouteProvider implements RouteProvider
{
	public function routes(): array
	{
		return [
			new FakeActionControllerRoute(),
			new FakeApiControllerRoute(),
			new FakeAsyncComponentControllerRoute(),
			new FakePageControllerRoute(),
			new FakeExceptionResolverRoute(),
		];
	}

	public function fallbackRoute(): RouteDefinition
	{
		return new FakeFallbackBaseControllerRoute();
	}
}
