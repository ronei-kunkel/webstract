<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Controller\FakePageController;

final class FakePageControllerRoute extends RoutePathTemplate implements RouteDefinition
{
	public static function getMethod(): RequestMethod
	{
		return RequestMethod::GET;
	}

	public static function getPattern(): string
	{
		return '/page[/]';
	}

	public static function getController(): string
	{
		return FakePageController::class;
	}

	public function getPathFormat(): string
	{
		return '/page';
	}
}
