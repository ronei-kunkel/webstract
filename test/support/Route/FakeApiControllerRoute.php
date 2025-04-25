<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Controller\FakeApiController;

final class FakeApiControllerRoute extends RoutePathTemplate implements RouteDefinition
{
	public static function getMethod(): RequestMethod
	{
		return RequestMethod::GET;
	}

	public static function getPattern(): string
	{
		return '/fake/{fake:\d+}/opa/{opa\d+}[/]';
	}

	public static function getController(): string
	{
		return FakeApiController::class;
	}

	public function getPathFormat(): string
	{
		return '/fake/%s/opa/%s';
	}
}
