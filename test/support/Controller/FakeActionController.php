<?php

namespace Test\Support\RoneiKunkel\Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RoneiKunkel\Webstract\Controller\ActionController;
use RoneiKunkel\Webstract\Route\RoutePathTemplate;
use Test\Support\RoneiKunkel\Webstract\Route\FakeRoute;

class FakeActionController extends ActionController
{
	public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$fakeRoute = new FakeRoute();
		return $this->createRedirectResponse($fakeRoute->withPathParams(1234, 1234567));
	}

	public function testCreateRedirectResponse(RoutePathTemplate $route): ResponseInterface
	{
		return $this->createRedirectResponse($route);
	}
};
