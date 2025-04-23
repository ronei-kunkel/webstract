<?php

declare(strict_types=1);

use Mockery\MockInterface;
use Psr\Http\Message\ResponseInterface;
use Test\Support\Controller\FakeApiController;
use Test\Support\Middleware\FakeWrongImplementationMiddleware;
use Webstract\Controller\Controller;
use Webstract\Request\RequestHandlerPipeline;

test('Should call controller when have no middlewares', function () {
	$controller = new FakeApiController($this->response, $this->stream);
	$pipeline = new RequestHandlerPipeline($controller);

	$response = $pipeline->handle($this->serverRequest);

	expect($response)->toBeInstanceOf(ResponseInterface::class);
	expect((string) $response->getBody())->toBe('{"message":"Hello, World!"}');
	expect($response->getHeaders())->toBe(['Content-Type' => ['application/json']]);
	expect($response->getStatusCode())->toBe(200);
});

test('Should thrown exception when middleware not implement PSR Middleware Interface', function () {
	/** @var Controller|MockInterface */
	$controller = Mockery::mock(Controller::class);
	$controller->shouldReceive('middlewares')->andReturn([new FakeWrongImplementationMiddleware()]);

	$pipeline = new RequestHandlerPipeline($controller);

	$pipeline->handle($this->serverRequest);
})->throws(RuntimeException::class, 'Middleware Test\Support\Middleware\FakeWrongImplementationMiddleware does not implement Psr\Http\Server\MiddlewareInterface');

