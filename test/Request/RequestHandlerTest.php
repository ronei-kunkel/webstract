<?php

declare(strict_types=1);

namespace Test\Request;

use Test\TestCase;

class RequestHandlerTest extends TestCase
{
	public function test_skipped_maintained_for_registration_purpouses(): void
	{
		$this->markTestSkipped('empty test, maintained for registration porpouses');
	}
}

// beforeEach(function () {
// 	$this->session = new FakeSessionHandler();
// 	$this->session->initSession();
// });

// afterEach(fn() => $this->session->destroySession());

// test('Request should hanlded properly', function () {
// 	$psr17Factory = new Psr17Factory();

// 	$container = new Container([
// 		TemplateEngineRenderer::class => new TwigTemplateEngineRenderer(),
// 		SessionHandler::class => new FakeSessionHandler(),
// 		StreamInterface::class => $psr17Factory->createStream(),
// 		ResponseInterface::class => $psr17Factory->createResponse(),
// 		PdfGenerator::class => DI\create(DompdfPdfGenerator::class)->constructor(Di\get(TemplateEngineRenderer::class)),
// 	]);

// 	$routeProvider = new FakeRouteProvider();
// 	$routeResolver = new FastRouteRouteResolver($routeProvider);
// 	$request = new ServerRequest('POST', '/some/123/path');
// 	$requestHandler = new RequestHandler(
// 		$routeResolver,
// 		$container,
// 	);

// 	$response = $requestHandler->handle($request);

// 	expect($response)->toBeInstanceOf(ResponseInterface::class);
// 	expect((string) $response->getBody())->toBe('{"some":"123"}');
// 	expect($response->getHeaders())->toBe([]);
// 	expect($response->getStatusCode())->toBe(200);
// });

// test('Should return 404 response when requested route are not registered', function () {
// 	$psr17Factory = new Psr17Factory();

// 	$container = new Container([
// 		TemplateEngineRenderer::class => new TwigTemplateEngineRenderer(),
// 		SessionHandler::class => new FakeSessionHandler(),
// 		StreamInterface::class => $psr17Factory->createStream(),
// 		ResponseInterface::class => $psr17Factory->createResponse(),
// 		PdfGenerator::class => DI\create(DompdfPdfGenerator::class)->constructor(Di\get(TemplateEngineRenderer::class)),
// 	]);

// 	$routeProvider = new FakeRouteProvider();
// 	$routeResolver = new FastRouteRouteResolver($routeProvider);
// 	$request = new ServerRequest('GET', '/not-registered');
// 	$requestHandler = new RequestHandler(
// 		$routeResolver,
// 		$container,
// 	);

// 	$response = $requestHandler->handle($request);

// 	expect($response)->toBeInstanceOf(ResponseInterface::class);
// 	expect((string) $response->getBody())->toBe('');
// 	expect($response->getHeaders())->toBe([]);
// 	expect($response->getStatusCode())->toBe(404);
// });

// test('Should return 405 response when requested route method are not allowed', function () {
// 	$psr17Factory = new Psr17Factory();

// 	$container = new Container([
// 		TemplateEngineRenderer::class => new TwigTemplateEngineRenderer(),
// 		SessionHandler::class => new FakeSessionHandler(),
// 		StreamInterface::class => $psr17Factory->createStream(),
// 		ResponseInterface::class => $psr17Factory->createResponse(),
// 		PdfGenerator::class => DI\create(DompdfPdfGenerator::class)->constructor(Di\get(TemplateEngineRenderer::class)),
// 	]);

// 	$routeProvider = new FakeRouteProvider();
// 	$routeResolver = new FastRouteRouteResolver($routeProvider);
// 	$request = new ServerRequest('POST', '/oops');
// 	$requestHandler = new RequestHandler(
// 		$routeResolver,
// 		$container,
// 	);

// 	$response = $requestHandler->handle($request);

// 	expect($response)->toBeInstanceOf(ResponseInterface::class);
// 	expect((string) $response->getBody())->toBe('');
// 	expect($response->getHeaders())->toBe([]);
// 	expect($response->getStatusCode())->toBe(405);
// });
