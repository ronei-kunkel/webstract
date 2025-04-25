<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Controller\FakeAsyncComponentController;

final class FakeAsyncComponentControllerRoute extends RoutePathTemplate implements RouteDefinition
{
	public static function getMethod(): RequestMethod
	{
		return RequestMethod::POST;
	}

	public static function getPattern(): string
	{
		return '/async-component[/]';
	}

	public static function getController(): string
	{
		return FakeAsyncComponentController::class;
	}

	public function getPathFormat(): string
	{
		return '/async-component';
	}
}
