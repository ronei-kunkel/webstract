<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use RoneiKunkel\Webstract\Controller;

function makeTestController(ResponseInterface $response, StreamInterface $stream): Controller
{
	return new class($response, $stream) extends Controller {
		public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
		{
			$this->streamInterface->write('Hello, World!');
			return $this->responseInterface->withBody($this->streamInterface);
		}
	};
}

test('Controller::__invoke writes to stream and returns the modified response', function () {
	$stream = $this->createMock(StreamInterface::class);
	$stream->expects($this->once())
		->method('write')
		->with('Hello, World!');

	$response = $this->createMock(ResponseInterface::class);
	$response->expects($this->once())
		->method('withBody')
		->with($stream)
		->willReturnSelf();

	$controller = makeTestController($response, $stream);

	$serverRequest = $this->createMock(ServerRequestInterface::class);

	$result = $controller($serverRequest);

	expect($result)->toBe($response);
});
