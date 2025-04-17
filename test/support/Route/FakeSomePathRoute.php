<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Controller\FakeActionController;

final class FakeSomePathRoute extends RoutePathTemplate implements RouteDefinition
{
	public function getMethod(): RequestMethod
	{
		return RequestMethod::POST;
	}

	public function getPattern(): string
	{
		// @todo can be used when we implemented controller input
		// return '@^/some/(?<some>\d+)/path/?$@';
		return '@^/some/\d+/path/?$@';
	}

	public function getPathFormat(): string
	{
		return '/some/%s/path';
	}

	public function getController(): string
	{
		return FakeActionController::class;
	}
}
