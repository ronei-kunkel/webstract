<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Controller\FakeController;

final class FakeFallbackBaseControllerRoute extends RoutePathTemplate implements RouteDefinition
{
	public static function getMethod(): RequestMethod
	{
		return RequestMethod::GET;
	}

	public static function getPattern(): string
	{
		return '/not-found[/]';
	}

	public static function getController(): string
	{
		return FakeController::class;
	}

	public function getPathFormat(): string
	{
		return '/not-found';
	}
}
