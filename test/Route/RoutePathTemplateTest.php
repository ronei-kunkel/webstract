<?php

declare(strict_types=1);

namespace Test\Route;

use Test\Support\Route\FakeRouteWithPathParams;
use Test\TestCase;
use Webstract\Route\Exception\RoutePathParamsUnexpectedParamValues;

class RoutePathTemplateTest extends TestCase
{
	public function test_RouteWithPathParams_ShouldRenderPathAsExpected(): void
	{
		$resourceId = '3';
		$anotherResourceId = 9;
		$renderedRoute = new FakeRouteWithPathParams()->withPathParams($resourceId, $anotherResourceId)->renderPath();
		$this->assertSame('/resource/3/another-resource/9', $renderedRoute);
	}

	public function test_RouteWithPathParams_ShouldThrowException_WhenPassLessParamsThanNumberOfPlaceholders(): void
	{
		$this->expectException(RoutePathParamsUnexpectedParamValues::class);
		$this->expectExceptionMessage('Route format `/resource/%s/another-resource/%s` has unfilled placeholders. Expected `2` but received `1`');

		new FakeRouteWithPathParams()->withPathParams('foo');
	}

	public function test_RouteWithPathParams_ShouldThrowException_WhenPassMoreParamsThanNumberOfPlaceholders(): void
	{
		$this->expectException(RoutePathParamsUnexpectedParamValues::class);
		$this->expectExceptionMessage('Route format `/resource/%s/another-resource/%s` has unfilled placeholders. Expected `2` but received `3`');

		new FakeRouteWithPathParams()->withPathParams('foo', 'bar', 'baz');
	}
}
