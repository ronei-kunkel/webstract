<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Controller\FakeController;

final class FakeFallbackBaseControllerRoute extends RoutePathTemplate implements RouteDefinition
{
	public function getMethod(): RequestMethod
	{
		return RequestMethod::GET;
	}

	public function getPattern(): string
	{
		// @todo can be used when we implemented controller input
		// return '@^/not-found/?$@';
		return '@^/not-found/?$@';
	}

	public function getPathFormat(): string
	{
		return '/not-found';
	}

	public function getController(): string
	{
		return FakeController::class;
	}
}
