<?php

declare(strict_types=1);

namespace Test\Route;

use Nyholm\Psr7\ServerRequest;
use Test\Support\Route\FakeGetUsersRoute;
use Test\Support\Route\FakePostUsersRoute;
use Test\Support\Route\FakeRouteProvider;
use Test\TestCase;
use Webstract\Request\RequestMethod;
use Webstract\Route\Exception\RoutePatternOverrideException;
use Webstract\Route\FastRouteRouter;
use Webstract\Route\RouteHandleable;
use Webstract\Route\RouterOutput;
use Webstract\Route\RouterOutputProvider;

final class FastRouteRouterTest extends TestCase
{
	public function test_ShouldResolveRoute_WhenMethodAndPatternAreValid(): void
	{
		$router = $this->createRouter([FakeGetUsersRoute::class], []);

		$output = $router->resolve(new ServerRequest(RequestMethod::GET->value, '/users/42'));

		$this->assertSame('users.show', $output->route::getController());
		$this->assertSame(['id' => '42'], $output->pathParams);
	}

	public function test_ShouldReturnRouteNotFound_WhenMethodIsValidButPatternIsInvalid(): void
	{
		$router = $this->createRouter([FakeGetUsersRoute::class], []);

		$output = $router->resolve(new ServerRequest(RequestMethod::GET->value, '/unknown'));

		$this->assertSame('route.not.found', $output->route::getController());
		$this->assertNull($output->pathParams);
	}

	public function test_ShouldReturnMethodNotAllowed_WhenPatternMatchesButMethodIsInvalid(): void
	{
		$router = $this->createRouter([FakeGetUsersRoute::class], []);

		$output = $router->resolve(new ServerRequest(RequestMethod::POST->value, '/users/42'));

		$this->assertSame('method.not.allowed', $output->route::getController());
		$this->assertNull($output->pathParams);
	}

	public function test_ShouldExtractRouteParams_WhenRouteContainsPathParameters(): void
	{
		$router = $this->createRouter([FakePostUsersRoute::class], []);

		$output = $router->resolve(new ServerRequest(RequestMethod::POST->value, '/users/777'));

		$this->assertSame(['id' => '777'], $output->pathParams);
	}

	public function test_ShouldThrowException_WhenCustomRouteOverridesFrameworkPattern(): void
	{
		$router = $this->createRouter([FakeGetUsersRoute::class], [FakeGetUsersRoute::class]);

		$this->expectException(RoutePatternOverrideException::class);
		$this->expectExceptionMessage('has pattern `/users/{id:\\d+}` that is reserved by framework.');

		$router->resolve(new ServerRequest(RequestMethod::GET->value, '/users/12'));
	}

	/** @param array<class-string<\Webstract\Route\RouteDefinition>> $baseRoutes */
	/** @param array<class-string<\Webstract\Route\RouteDefinition>> $customRoutes */
	private function createRouter(array $baseRoutes, array $customRoutes): FastRouteRouter
	{
		return new FastRouteRouter(
			new FakeRouteProvider($customRoutes),
			new class() extends RouterOutputProvider {
				public function routeNotFound(): RouterOutput
				{
					return new RouterOutput(new class() implements RouteHandleable {
						public static function getController(): string
						{
							return 'route.not.found';
						}
					}, null);
				}

				public function methodNotAllowed(): RouterOutput
				{
					return new RouterOutput(new class() implements RouteHandleable {
						public static function getController(): string
						{
							return 'method.not.allowed';
						}
					}, null);
				}
			},
			new FakeRouteProvider($baseRoutes)
		);
	}
}
