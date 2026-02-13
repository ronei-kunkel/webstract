<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Controller\FakeActionControllerThatCreateRedirectResponse;

final class FakeActionRoute extends RoutePathTemplate implements RouteDefinition
{
	public static function getMethod(): RequestMethod
	{
		return RequestMethod::POST;
	}

	public static function getPattern(): string
	{
		return '/';
	}

	public static function getController(): string
	{
		return FakeActionControllerThatCreateRedirectResponse::class;
	}

	public function getPathFormat(): string
	{
		return '/';
	}
}
