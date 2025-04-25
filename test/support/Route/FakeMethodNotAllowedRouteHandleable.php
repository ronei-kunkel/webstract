<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Test\Support\Controller\FakeMethodNotAllowedController;
use Webstract\Route\RouteHandleable;

final class FakeMethodNotAllowedRouteHandleable implements RouteHandleable
{
	public static function getController(): string
	{
		return FakeMethodNotAllowedController::class;
	}
}
