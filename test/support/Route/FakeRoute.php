<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Controller\FakeApiController;

final class FakeRoute extends RoutePathTemplate implements RouteDefinition
{
	public function getMethod(): RequestMethod
	{
		return RequestMethod::GET;
	}

	public function getPattern(): string
	{
		// @todo can be used when we implemented controller input
		// return '@^/fake/(?<fake>\d+)/opa/(?<opa>\d+)/?$@';
		return '@^/fake/\d+/opa/\d+/?$@';
	}

	public function getPathFormat(): string
	{
		return '/fake/%s/opa/%s';
	}

	public function getController(): string
	{
		return FakeApiController::class;
	}
}
