<?php

declare(strict_types=1);

namespace Webstract\Route;

use Psr\Http\Message\ServerRequestInterface;

final class RouteResolver
{
	public function __construct(private readonly RouteProvider $routeProvider) {}

	public function resolve(ServerRequestInterface $serverRequest): RouteDefinition
	{
		$method = $serverRequest->getMethod();
		$path = $serverRequest->getUri()->getPath();

		foreach ($this->routeProvider->routes() as $route) {
			if ($route->getMethod()->name !== $method) {
				continue;
			}

			if (preg_match($route->getPattern(), $path)) {
				return $route;
			}
		}

		return $this->routeProvider->fallbackRoute();
	}
}
