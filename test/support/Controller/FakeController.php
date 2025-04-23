<?php

namespace Test\Support\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webstract\Controller\Controller;

class FakeController extends Controller
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$this->streamInterface->write('Hello, World!');
		return $this->responseInterface->withBody($this->streamInterface);
	}
}
