<?php

namespace Test\Support\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Test\Support\Middleware\FakeFirstMiddleware;
use Test\Support\Middleware\FakeSecondMiddleware;
use Webstract\Controller\Controller;

final class FakeControllerWithMiddlewares extends Controller
{
	public function __construct(
		private readonly ResponseInterface $response,
	) {}

	public function middlewares(): array
	{
		return [
			FakeFirstMiddleware::class,
			FakeSecondMiddleware::class
		];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$response = $this->response;
		foreach ($serverRequest->getHeaders() as $key => $value) {
			$response = $response->withHeader($key, $value);
		}
		return $response->withHeader('X-Controller', 'controller-value');
	}
}
