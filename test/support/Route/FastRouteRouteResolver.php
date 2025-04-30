<?php

declare(strict_types=1);

namespace Test\Support\Route;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RouteHandleable;
use Webstract\Route\RouteResolver;
use Webstract\Route\RouteResolverOutput;

use function FastRoute\simpleDispatcher;

final class FastRouteRouteResolver implements RouteResolver
{
	public function __construct(private readonly FakeRouteProvider $routeProvider) {}

	public function resolve(ServerRequestInterface $serverRequest): RouteResolverOutput
	{
		/** @var RouteDefinition[] $routes */
		$routes = $this->routeProvider->getRoutes();
		$dispatcher = simpleDispatcher(function (RouteCollector $collector) use ($routes) {
			foreach ($routes as $route) {
				$collector->addRoute($route::getMethod()->value, $route::getPattern(), $route::getController());
			}
		});

		$routeInfo = $dispatcher->dispatch($serverRequest->getMethod(), $serverRequest->getUri()->getPath());

		return match ($routeInfo[0]) {
			Dispatcher::NOT_FOUND => $this->fallback(new FakeNotFoundRouteHandleable()),
			Dispatcher::METHOD_NOT_ALLOWED => $this->fallback(new FakeMethodNotAllowedRouteHandleable()),
			Dispatcher::FOUND => $this->found($routes, $routeInfo),
		};
	}

	private function fallback(RouteHandleable $route): RouteResolverOutput
	{
		return new RouteResolverOutput($route, null);
	}

	private function found(array &$routes, array $routeInfo): RouteResolverOutput
	{
		return new RouteResolverOutput(new $routes[$routeInfo[1]], $routeInfo[2]);
	}
}
