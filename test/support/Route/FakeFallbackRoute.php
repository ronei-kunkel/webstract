<?php

declare(strict_types=1);

namespace Test\Support\RoneiKunkel\Webstract\Route;

use RoneiKunkel\Webstract\Request\RequestMethod;
use RoneiKunkel\Webstract\Route\RouteDefinition;
use RoneiKunkel\Webstract\Route\RoutePathTemplate;
use Test\Support\RoneiKunkel\Webstract\Controller\FakeController;

final class FakeFallbackRoute extends RoutePathTemplate implements RouteDefinition
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
