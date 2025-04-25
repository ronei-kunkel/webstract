<?php

namespace Test\Support\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webstract\Controller\Controller;

class FakeNotFoundController extends Controller
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		return $this->responseInterface->withStatus(404);
	}
}
