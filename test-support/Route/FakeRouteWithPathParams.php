<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Route\RoutePathTemplate;

final class FakeRouteWithPathParams extends RoutePathTemplate
{
	public function getPathFormat(): string
	{
		return '/resource/%s/another-resource/%s';
	}
}
