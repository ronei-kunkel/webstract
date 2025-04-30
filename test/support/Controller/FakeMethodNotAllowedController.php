<?php

namespace Test\Support\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webstract\Controller\Controller;

class FakeMethodNotAllowedController extends Controller
{
	public function __construct(
		protected readonly ResponseInterface $response,
	) {}

	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		return $this->response->withStatus(405);
	}
}
