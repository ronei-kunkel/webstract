<?php

declare(strict_types=1);

namespace Test\Route;

use Test\Support\Route\FakeRouteWithPathParams;
use Test\TestCase;
use Webstract\Route\Exception\RoutePathParamsUnexpectedParamValues;
use Webstract\Route\Exception\RoutePathTemplateUnfilledParamsException;

final class RouteExceptionTest extends TestCase
{
	public function test_RoutePathParamsUnexpectedParamValues_ShouldReturnExpectedMessage(): void
	{
		$exception = new RoutePathParamsUnexpectedParamValues(new FakeRouteWithPathParams()->getPathFormat(), 2, 1);

		$this->assertSame('Route format `/resource/%s/another-resource/%s` has unfilled placeholders. Expected `2` but received `1`', $exception->getMessage());
	}
}
