<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Test\Support\RoneiKunkel\Webstract\Controller\FakeActionController;
use Test\Support\RoneiKunkel\Webstract\Route\FakeRoute;
use Test\Support\RoneiKunkel\Webstract\Session\FakeSessionManager;

test('should works properly when invoked', function () {
	$controller = new FakeActionController($this->response, $this->stream, new FakeSessionManager());

	$result = $controller($this->serverRequest);

	expect($result)->toBeInstanceOf(ResponseInterface::class);
	expect((string) $result->getBody())->toBe('');
	expect($result->getHeaders())->toBe(['location' => ['/fake/1234/opa/1234567']]);
	expect($result->getStatusCode())->toBe(303);
});


test('should throw exception when pass less parameters than was needed', function () {
	$controller = new FakeActionController($this->response, $this->stream, new FakeSessionManager());

	$controller->testCreateRedirectResponse((new FakeRoute())->withPathParams('1234'));
})->throws(RuntimeException::class, 'Route has unfilled resources. Output: /fake/1234/opa/%s');

test('should throw exception when pass route without params when it needed', function () {
	$controller = new FakeActionController($this->response, $this->stream, new FakeSessionManager());

	$controller->testCreateRedirectResponse(new FakeRoute());
})->throws(RuntimeException::class, 'Try render route before define params with $this->withParams(...$params) method');
