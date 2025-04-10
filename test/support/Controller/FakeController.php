<?php

namespace Test\Support\RoneiKunkel\Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RoneiKunkel\Webstract\Controller\Controller;

class FakeController extends Controller
{
	public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$this->streamInterface->write('Hello, World!');
		return $this->responseInterface->withBody($this->streamInterface);
	}
}
