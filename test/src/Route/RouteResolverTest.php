<?php

declare(strict_types=1);

use Nyholm\Psr7\ServerRequest;
use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RouteProvider;
use Webstract\Route\RouteResolver;
use Test\Support\Controller\FakeActionController;
use Test\Support\Controller\FakeController;
use Test\Support\Route\FakeFallbackBaseControllerRoute;
use Test\Support\Route\FakeApiControllerRoute;
use Test\Support\Route\FakeActionControllerRoute;

test('should resolve properly', function () {
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
	$serverRequest = new ServerRequest('POST', 'http://localhost/some/11/path');
	$routeResolver = new RouteResolver($routeProvider);

	$routeDefinition = $routeResolver->resolve($serverRequest);

	expect($routeDefinition)->toBeInstanceOf(FakeActionControllerRoute::class);
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
				new FakeApiControllerRoute(),
				new FakeActionControllerRoute(),
			];
		}

		public function fallbackRoute(): RouteDefinition
		{
			return new FakeFallbackBaseControllerRoute();
		}
	};
	$serverRequest = new ServerRequest('POST', 'http://localhost/some/11/path/11');
	$routeResolver = new RouteResolver($routeProvider);

	$routeDefinition = $routeResolver->resolve($serverRequest);

	expect($routeDefinition)->toBeInstanceOf(FakeFallbackBaseControllerRoute::class);
	expect($routeDefinition->getMethod())->toEqual(RequestMethod::GET);
	expect($routeDefinition->getController())->toBe(FakeController::class);
	expect($routeDefinition->getPattern())->toBe('@^/not-found/?$@');
});
