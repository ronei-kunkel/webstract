<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Test\Support\Controller\FakeUnextendedAnyAbstractController;
use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;

final class FakeExceptionResolverRoute extends RoutePathTemplate implements RouteDefinition
{
	public static function getMethod(): RequestMethod
	{
		return RequestMethod::PATCH;
	}

	public static function getPattern(): string
	{
		return '/oops[/]';
	}

	public static function getController(): string
	{
		return FakeUnextendedAnyAbstractController::class;
	}

	public function getPathFormat(): string
	{
		return '/oops';
	}
}
