<?php

declare(strict_types=1);

namespace Test\Support\Route;

final class FakeRouteProvider
{
	public function getRoutes(): array
	{
		return [
			FakeActionControllerRoute::getController() => FakeActionControllerRoute::class,
			FakeApiControllerRoute::getController() => FakeApiControllerRoute::class,
			FakeAsyncComponentControllerRoute::getController() => FakeAsyncComponentControllerRoute::class,
			FakePageControllerRoute::getController() => FakePageControllerRoute::class,
			FakeExceptionResolverRoute::getController() => FakeExceptionResolverRoute::class,
			FakeFallbackBaseControllerRoute::getController() => FakeFallbackBaseControllerRoute::class,
		];
	}
}
