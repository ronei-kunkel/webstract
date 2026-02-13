<?php

declare(strict_types=1);

namespace Webstract\Route;

use Webstract\Route\RouteHandleable;
use Webstract\Route\RouterOutput;

abstract class RouterOutputProvider
{
	abstract public function routeNotFound(): RouterOutput;
	abstract public function methodNotAllowed(): RouterOutput;

	public function resolved(string $controllerClass, array $params): RouterOutput
	{
		$route = new class($controllerClass) implements RouteHandleable {
			private static string $controllerClass;
			public function __construct(
				string $controllerClass
			) {
				self::$controllerClass = $controllerClass;
			}

			public static function getController(): string
			{
				return self::$controllerClass;
			}
		};

		return new RouterOutput($route, $params);
	}
}
