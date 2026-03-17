<?php

namespace Test\Support\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Test\Support\Route\FakeActionRoute;
use Webstract\Controller\AsyncComponentController;

class FakeAsyncComponentControllerThatCreateAsyncRedirectResponse extends AsyncComponentController
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$route = new FakeActionRoute();
		return $this->createRedirectResponse($route);
	}
};
