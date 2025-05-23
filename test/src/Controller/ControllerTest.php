<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Test\Support\Controller\FakeController;

test('Controller::__invoke writes to stream and returns the modified response', function () {
	$controller = new FakeController($this->response, $this->stream);

	$result = $controller->handle($this->serverRequest);

	expect($result)->toBeInstanceOf(ResponseInterface::class);
	expect((string) $result->getBody())->toBe('Hello, World!');
});
