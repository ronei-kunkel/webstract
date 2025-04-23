<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Test\Support\Controller\FakeUnextendedAnyAbstractController;
use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;

final class FakeExceptionResolverRoute extends RoutePathTemplate implements RouteDefinition
{
	public function getMethod(): RequestMethod
	{
		return RequestMethod::PATCH;
	}

	public function getPattern(): string
	{
		// @todo can be used when we implemented controller input
		// return '@^/oops/?$@';
		return '@^/oops/?$@';
	}

	public function getController(): string
	{
		return FakeUnextendedAnyAbstractController::class;
	}

	public function getPathFormat(): string
	{
		return '/oops';
	}
}
