<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Controller\FakeActionController;
use Test\Support\Controller\FakePageController;

final class FakePageControllerRoute extends RoutePathTemplate implements RouteDefinition
{
	public function getMethod(): RequestMethod
	{
		return RequestMethod::GET;
	}

	public function getPattern(): string
	{
		return '@^/page/?$@';
	}

	public function getPathFormat(): string
	{
		return '/page';
	}

	public function getController(): string
	{
		return FakePageController::class;
	}
}
