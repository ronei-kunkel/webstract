<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Test\Support\Controller\FakeNotFoundController;
use Webstract\Route\RouteHandleable;

final class FakeNotFoundRouteHandleable implements RouteHandleable
{
	public static function getController(): string
	{
		return FakeNotFoundController::class;
	}
}
