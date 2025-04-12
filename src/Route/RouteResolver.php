<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Route;

use Psr\Http\Message\RequestInterface;

final class RouteResolver
{
	public function __construct(private readonly RouteProvider $routeProvider) {}

	public function resolve(RequestInterface $request): RouteDefinition
	{
		$method = $request->getMethod();
		$path = $request->getUri()->getPath();

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
