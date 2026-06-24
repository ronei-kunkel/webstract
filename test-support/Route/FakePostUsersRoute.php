<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Request\RequestMethod;
use Webstract\Route\RouteDefinition;

final class FakePostUsersRoute implements RouteDefinition
{
	public static function getMethod(): RequestMethod
	{
		return RequestMethod::POST;
	}

	public static function getPattern(): string
	{
		return '/users/{id:\\d+}';
	}

	public static function getController(): string
	{
		return 'users.update';
	}
}
