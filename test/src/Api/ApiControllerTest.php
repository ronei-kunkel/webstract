<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RoneiKunkel\Webstract\Api\ApiController;

interface TestableApiController
{
	public function testCreateJsonResponse($content): ResponseInterface;
}

function makeTestApiController(ResponseInterface $response, StreamInterface $stream): TestableApiController
{
	return new class($response, $stream) extends ApiController implements TestableApiController
	{
		public function __invoke($serverRequest): ResponseInterface
		{
			return $this->createJsonResponse(['message' => 'Hello, World!']);
		}

		public function testCreateJsonResponse($content): ResponseInterface
		{
			return $this->createJsonResponse($content);
		}
	};
}

test('createJsonResponse writes JSON content and sets the correct header', function () {
	$stream = $this->createMock(StreamInterface::class);
	$stream->expects($this->once())
		->method('write')
		->with('{"message":"Hello, World!"}');

	$response = $this->createMock(ResponseInterface::class);
	$response->expects($this->once())
		->method('withBody')
		->with($stream)
		->willReturnSelf();

	$response->expects($this->once())
		->method('withHeader')
		->with('content-type', 'application/json')
		->willReturnSelf();

	$controller = makeTestApiController($response, $stream);
	$result = $controller->testCreateJsonResponse(['message' => 'Hello, World!']);

	expect($result)->toBe($response);
});

test('createJsonResponse handles null content gracefully', function () {
	$stream = $this->createMock(StreamInterface::class);
	$stream->expects($this->never())
		->method('write');

	$response = $this->createMock(ResponseInterface::class);
	$response->expects($this->once())
		->method('withBody')
		->with($stream)
		->willReturnSelf();

	$response->expects($this->once())
		->method('withHeader')
		->with('content-type', 'application/json')
		->willReturnSelf();

	$controller = makeTestApiController($response, $stream);
	$result = $controller->testCreateJsonResponse(null);

	expect($result)->toBe($response);
});

test('createJsonResponse writes JSONSerializable content', function () {
	$content = new class implements JsonSerializable {
		public function jsonSerialize(): array
		{
			return ['status' => 'success'];
		}
	};

	$stream = $this->createMock(StreamInterface::class);
	$stream->expects($this->once())
		->method('write')
		->with('{"status":"success"}');

	$response = $this->createMock(ResponseInterface::class);
	$response->expects($this->once())
		->method('withBody')
		->with($stream)
		->willReturnSelf();

	$response->expects($this->once())
		->method('withHeader')
		->with('content-type', 'application/json')
		->willReturnSelf();

	$controller = makeTestApiController($response, $stream);
	$result = $controller->testCreateJsonResponse($content);

	expect($result)->toBe($response);
});
