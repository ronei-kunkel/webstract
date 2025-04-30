<?php

declare(strict_types=1);

use DI\Container;
use Mockery\MockInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Test\Support\Controller\FakeApiController;
use Test\Support\Controller\FakeControllerWithMiddlewares;
use Test\Support\Controller\FakeControllerWithWrongMiddleware;
use Test\Support\Pdf\DompdfPdfGenerator;
use Test\Support\Session\FakeSessionHandler;
use Test\Support\TemplateEngine\TwigTemplateEngineRenderer;
use Webstract\Pdf\PdfGenerator;
use Webstract\Request\RequestHandlerPipeline;
use Webstract\Session\SessionHandler;
use Webstract\TemplateEngine\TemplateEngineRenderer;

test('Should call controller when have no middlewares', function () {
	$psr17Factory = new Psr17Factory();

	$container = new Container([
		TemplateEngineRenderer::class => new TwigTemplateEngineRenderer(),
		SessionHandler::class => new FakeSessionHandler(),
		StreamInterface::class => $psr17Factory->createStream(),
		ResponseInterface::class => $psr17Factory->createResponse(),
		PdfGenerator::class => DI\create(DompdfPdfGenerator::class)->constructor(Di\get(TemplateEngineRenderer::class)),
	]);

	$pipeline = new RequestHandlerPipeline(FakeApiController::class, $container);

	$response = $pipeline->handle($this->serverRequest);

	expect($response)->toBeInstanceOf(ResponseInterface::class);
	expect((string) $response->getBody())->toBe('{"message":"Hello, World!"}');
	expect($response->getHeaders())->toBe(['Content-Type' => ['application/json']]);
	expect($response->getStatusCode())->toBe(200);
});

test('Should call controller when have middlewares', function () {
	$psr17Factory = new Psr17Factory();

	$container = new Container([
		TemplateEngineRenderer::class => new TwigTemplateEngineRenderer(),
		SessionHandler::class => new FakeSessionHandler(),
		StreamInterface::class => $psr17Factory->createStream(),
		ResponseInterface::class => $psr17Factory->createResponse(),
		PdfGenerator::class => DI\create(DompdfPdfGenerator::class)->constructor(Di\get(TemplateEngineRenderer::class)),
	]);

	$pipeline = new RequestHandlerPipeline(FakeControllerWithMiddlewares::class, $container);

	$response = $pipeline->handle($this->serverRequest);

	expect($response)->toBeInstanceOf(ResponseInterface::class);
	expect((string) $response->getBody())->toBe('');
	expect($response->getHeaders())->toBe(['X-First-Middleware' => ['first-middleware-value'], 'X-Second-Middleware' => ['second-middleware-value'], 'X-Controller' => ['controller-value']]);
	expect($response->getStatusCode())->toBe(200);
});

test('Should thrown exception when middleware not implement PSR Middleware Interface', function () {
	$psr17Factory = new Psr17Factory();

	$container = new Container([
		TemplateEngineRenderer::class => new TwigTemplateEngineRenderer(),
		SessionHandler::class => new FakeSessionHandler(),
		StreamInterface::class => $psr17Factory->createStream(),
		ResponseInterface::class => $psr17Factory->createResponse(),
		PdfGenerator::class => DI\create(DompdfPdfGenerator::class)->constructor(Di\get(TemplateEngineRenderer::class)),
	]);

	$pipeline = new RequestHandlerPipeline(FakeControllerWithWrongMiddleware::class, $container);

	$pipeline->handle($this->serverRequest);

})->throws(RuntimeException::class, 'Middleware Test\Support\Middleware\FakeWrongImplementationMiddleware does not implement Psr\Http\Server\MiddlewareInterface');

test('Pass attributes from router path to controller', function () {
	$psr17Factory = new Psr17Factory();
	/** @var SessionHandler|MockInterface */
	$session = Mockery::mock(SessionHandler::class);

	$container = new Container([
		TemplateEngineRenderer::class => new TwigTemplateEngineRenderer(),
		SessionHandler::class => new FakeSessionHandler(),
		StreamInterface::class => $psr17Factory->createStream(),
		ResponseInterface::class => $psr17Factory->createResponse(),
		PdfGenerator::class => DI\create(DompdfPdfGenerator::class)->constructor(Di\get(TemplateEngineRenderer::class)),
	]);

	$request = new ServerRequest('GET', '/some/middleware');

	$pipeline = new RequestHandlerPipeline(FakeControllerWithMiddlewares::class, $container);

	$response = $pipeline->handle($request);

	expect($response->getHeaders())->toBe(['X-First-Middleware' => ['first-middleware-value'], 'X-Second-Middleware' => ['second-middleware-value'], 'X-Controller' => ['controller-value']]);
});
