<?php

declare(strict_types=1);

use Webstract\Route\RouteDefinition;
use Webstract\Route\RouteProvider;
use Test\Support\Route\FakeFallbackRoute;
use Test\Support\Route\FakeRoute;
use Test\Support\Route\FakeSomePathRoute;

test('should return registered routes', function () {
	$routeProvider = new class implements RouteProvider {
		/**
		 * @return RouteDefinition[]
		 */
		public function routes(): array
		{
			return [
				new FakeRoute(),
				new FakeSomePathRoute(),
			];
		}

		public function fallbackRoute(): RouteDefinition
		{
			return new FakeFallbackRoute();
		}
	};

	expect($routeProvider->routes()[0])->toBeInstanceOf(FakeRoute::class);
	expect($routeProvider->routes()[1])->toBeInstanceOf(FakeSomePathRoute::class);
});

test('fallback route should return a falback route', function () {
	$routeProvider = new class implements RouteProvider {
		/**
		 * @return RouteDefinition[]
		 */
		public function routes(): array
		{
			return [
				new FakeRoute(),
				new FakeSomePathRoute(),
			];
		}

		public function fallbackRoute(): RouteDefinition
		{
			return new FakeFallbackRoute();
		}
	};

	expect($routeProvider->fallbackRoute())->toBeInstanceOf(FakeFallbackRoute::class);
});