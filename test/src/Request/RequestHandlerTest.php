<?php

declare(strict_types=1);

use Mockery\MockInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Test\Support\Route\FakeActionControllerRoute;
use Test\Support\Route\FakeAsyncComponentControllerRoute;
use Test\Support\Route\FakePageControllerRoute;
use Test\Support\Route\FakeRouteProvider;
use Test\Support\Session\FakeSessionHandler;
use Test\Support\TemplateEngine\TwigTemplateEngineRenderer;
use Webstract\Request\RequestHandler;
use Webstract\Route\RouteDefinition;
use Webstract\Route\RoutePathTemplate;
use Webstract\Session\SessionHandler;

beforeEach(function () {
	$this->session = new FakeSessionHandler();
	$this->session->initSession();
});

afterEach(fn() => $this->session->destroySession());

test('Request should hanlded properly', function () {
	$routeProvider = new FakeRouteProvider();
	$templateEngine = new TwigTemplateEngineRenderer();
	$psr17 = new Psr17Factory();
	$request = new ServerRequest('POST', '/some/123/path');
	$requestHandler = new RequestHandler(
		$routeProvider,
		$this->session,
		$templateEngine,
		$psr17,
		$psr17
	);

	$response = $requestHandler->handle($request);

	expect($response)->toBeInstanceOf(ResponseInterface::class);
	expect((string) $response->getBody())->toBe('');
	expect($response->getHeaders())->toBe(['Location' => ['/fake/1234/opa/1234567']]);
	expect($response->getStatusCode())->toBe(303);
});

test('Should thrown exception when cannot handle the route controller', function () {
	$routeProvider = new FakeRouteProvider();
	$templateEngine = new TwigTemplateEngineRenderer();
	$psr17 = new Psr17Factory();
	$request = new ServerRequest('POST', '/oops');
	$requestHandler = new RequestHandler(
		$routeProvider,
		$this->session,
		$templateEngine,
		$psr17,
		$psr17
	);

	$requestHandler->handle($request);
})->throws(RuntimeException::class, 'Cannot handle controller: Test\Support\Controller\FakeController');

test('Should init session when instantiate controller based on AsyncComponentController, PageController or ActionController', function (string $method, string $path) {
	$routeProvider = new FakeRouteProvider();
	$templateEngine = new TwigTemplateEngineRenderer();
	$psr17 = new Psr17Factory();
	$request = new ServerRequest($method, $path);
	/** @var SessionHandler|MockInterface */
	$session = Mockery::mock(SessionHandler::class);

	$session->shouldReceive('initSession')->once();

	$requestHandler = new RequestHandler(
		$routeProvider,
		$session,
		$templateEngine,
		$psr17,
		$psr17
	);
	$requestHandler->handle($request);
})->with([
	[
		'POST', //FakeAsyncComponentControllerRoute
		'/async-component',
	],
	[
		'GET', //FakePageControllerRoute
		'/page',
	],
	[
		'POST', //FakePageControllerRoute
		'/some/123/path',
	],
]);

test('Should NOT INIT session when instantiate controller based on ApiController', function () {
	$routeProvider = new FakeRouteProvider();
	$templateEngine = new TwigTemplateEngineRenderer();
	$psr17 = new Psr17Factory();
	$request = new ServerRequest('GET', '/fake/123/opa/123');
	/** @var SessionHandler|MockInterface */
	$session = Mockery::mock(SessionHandler::class);

	$session->shouldReceive('initSession')->never();

	$requestHandler = new RequestHandler(
		$routeProvider,
		$session,
		$templateEngine,
		$psr17,
		$psr17
	);
	$requestHandler->handle($request);
});
