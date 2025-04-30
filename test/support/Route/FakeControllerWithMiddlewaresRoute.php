<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Controller\FakeControllerWithMiddlewares;

final class FakeControllerWithMiddlewaresRoute extends RoutePathTemplate implements RouteDefinition
{
	public static function getMethod(): RequestMethod
	{
		return RequestMethod::GET;
	}

	public static function getPattern(): string
	{
		return '/some/middleware[/]';
	}

	public static function getController(): string
	{
		return FakeControllerWithMiddlewares::class;
	}

	public function getPathFormat(): string
	{
		return '/some/middleware';
	}
}
