<?php

declare(strict_types=1);

use Webstract\Route\RouteDefinition;
use Webstract\Route\RouteProvider;
use Test\Support\Route\FakeFallbackBaseControllerRoute;
use Test\Support\Route\FakeApiControllerRoute;
use Test\Support\Route\FakeActionControllerRoute;

test('should return registered routes', function () {
	$routeProvider = new class implements RouteProvider {
		/**
		 * @return RouteDefinition[]
		 */
		public function routes(): array
		{
			return [
				new FakeApiControllerRoute(),
				new FakeActionControllerRoute(),
			];
		}

		public function fallbackRoute(): RouteDefinition
		{
			return new FakeFallbackBaseControllerRoute();
		}
	};

	expect($routeProvider->routes()[0])->toBeInstanceOf(FakeApiControllerRoute::class);
	expect($routeProvider->routes()[1])->toBeInstanceOf(FakeActionControllerRoute::class);
});

test('fallback route should return a falback route', function () {
	$routeProvider = new class implements RouteProvider {
		/**
		 * @return RouteDefinition[]
		 */
		public function routes(): array
		{
			return [
				new FakeApiControllerRoute(),
				new FakeActionControllerRoute(),
			];
		}

		public function fallbackRoute(): RouteDefinition
		{
			return new FakeFallbackBaseControllerRoute();
		}
	};

	expect($routeProvider->fallbackRoute())->toBeInstanceOf(FakeFallbackBaseControllerRoute::class);
});