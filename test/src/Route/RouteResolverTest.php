<?php

declare(strict_types=1);

use Nyholm\Psr7\Request;
use RoneiKunkel\Webstract\Request\RequestMethod;
use RoneiKunkel\Webstract\Route\RouteDefinition;
use RoneiKunkel\Webstract\Route\RouteProvider;
use RoneiKunkel\Webstract\Route\RouteResolver;
use Test\Support\RoneiKunkel\Webstract\Controller\FakeActionController;
use Test\Support\RoneiKunkel\Webstract\Controller\FakeController;
use Test\Support\RoneiKunkel\Webstract\Route\FakeFallbackRoute;
use Test\Support\RoneiKunkel\Webstract\Route\FakeRoute;
use Test\Support\RoneiKunkel\Webstract\Route\FakeSomePathRoute;

test('should resolve properly', function () {
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
	$request = new Request('POST', 'http://localhost/some/11/path');
	$routeResolver = new RouteResolver($routeProvider);

	$routeDefinition = $routeResolver->resolve($request);

	expect($routeDefinition)->toBeInstanceOf(FakeSomePathRoute::class);
	expect($routeDefinition->getMethod())->toEqual(RequestMethod::POST);
	expect($routeDefinition->getController())->toBe(FakeActionController::class);
	expect($routeDefinition->getPattern())->toBe('@^/some/\d+/path/?$@');
});

test('should return fallback route when not match request', function () {
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
	$request = new Request('POST', 'http://localhost/some/11/path/11');
	$routeResolver = new RouteResolver($routeProvider);

	$routeDefinition = $routeResolver->resolve($request);

	expect($routeDefinition)->toBeInstanceOf(FakeFallbackRoute::class);
	expect($routeDefinition->getMethod())->toEqual(RequestMethod::GET);
	expect($routeDefinition->getController())->toBe(FakeController::class);
	expect($routeDefinition->getPattern())->toBe('@^/not-found/?$@');
});
