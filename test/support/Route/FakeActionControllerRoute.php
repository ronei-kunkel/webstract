<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Controller\FakeActionController;

final class FakeActionControllerRoute extends RoutePathTemplate implements RouteDefinition
{
	public static function getMethod(): RequestMethod
	{
		return RequestMethod::POST;
	}

	public static function getPattern(): string
	{
		return '/some/{some:\d+}/path[/]';
	}

	public static function getController(): string
	{
		return FakeActionController::class;
	}

	public function getPathFormat(): string
	{
		return '/some/%s/path';
	}
}
