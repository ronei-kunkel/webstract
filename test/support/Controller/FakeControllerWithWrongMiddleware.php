<?php

declare(strict_types=1);

namespace Test\Support\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Test\Support\Middleware\FakeWrongImplementationMiddleware;
use Webstract\Controller\Controller;

final class FakeControllerWithWrongMiddleware extends Controller
{
	public function __construct(
		private readonly ResponseInterface $response,
	) {}

	public function middlewares(): array
	{
		return [
			FakeWrongImplementationMiddleware::class,
		];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		return $this->response;
	}
}
