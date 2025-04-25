<?php

declare(strict_types=1);

namespace Test\Support\Route;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;
use Webstract\Route\RouteHandleable;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RouteResolver;

use function FastRoute\simpleDispatcher;

final class FastRouteRouteResolver implements RouteResolver
{
	public function __construct(private readonly FakeRouteProvider $routeProvider) {}

	public function resolve(ServerRequestInterface $serverRequest): RouteHandleable
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
			Dispatcher::NOT_FOUND => new FakeNotFoundRouteHandleable,
			Dispatcher::METHOD_NOT_ALLOWED => new FakeMethodNotAllowedRouteHandleable,
			Dispatcher::FOUND => new $routes[$routeInfo[1]],
		};
	}
}


		// switch () {
		// 	case :
		// 		// ... 404 Not Found
		// 		break;
		// 	case :
		// 		$allowedMethods = $routeInfo[1];
		// 		// ... 405 Method Not Allowed
		// 		break;
		// 	case :
		// 		$handler = $routeInfo[1];
		// 		$vars = $routeInfo[2];
		// 		// ... call $handler with $vars
		// 		break;
		// }