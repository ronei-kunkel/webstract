<?php

namespace Test\Support\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webstract\Controller\ActionController;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Route\FakeApiControllerRoute;

class FakeActionController extends ActionController
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$this->stream->write(json_encode($serverRequest->getAttributes()));
		return $this->response->withBody($this->stream);
	}

	public function testCreateRedirectResponse(RoutePathTemplate $route): ResponseInterface
	{
		return $this->createRedirectResponse($route);
	}
};
