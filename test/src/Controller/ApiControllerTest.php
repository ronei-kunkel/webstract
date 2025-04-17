<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Test\Support\Controller\FakeApiController;
use Test\Support\JsonSerializableClass;

test('should works properly when invoked', function () {
	$controller = new FakeApiController($this->response, $this->stream);

	$result = $controller($this->serverRequest);

	expect($result)->toBeInstanceOf(ResponseInterface::class);
	expect((string) $result->getBody())->toBe('{"message":"Hello, World!"}');
	expect($result->getHeaders())->toBe(['Content-Type' => ['application/json']]);
	expect($result->getStatusCode())->toBe(200);
});

test('createJsonResponse handles expected entries gracefully', function (mixed $expectedContent, string $expectedBodyResult) {
	$controller = new FakeApiController($this->response, $this->stream);

	$result = $controller->testCreateJsonResponse($expectedContent);

	expect($result)->toBeInstanceOf(ResponseInterface::class);
	expect((string) $result->getBody())->toBe($expectedBodyResult);
	expect($result->getHeaders())->toBe(['Content-Type' => ['application/json']]);
	expect($result->getStatusCode())->toBe(200);
})->with([
	[null, ''],
	[['number' => 1], '{"number":1}'],
	[new JsonSerializableClass(), '{"status":"success"}']
]);

test('createJsonResponse throw exception when value not handable', function (mixed $expectedContent) {
	$controller = new FakeApiController($this->response, $this->stream);

	$controller->testCreateJsonResponse($expectedContent);
})->with([1, '', '1', new stdClass()])->throws(TypeError::class);
