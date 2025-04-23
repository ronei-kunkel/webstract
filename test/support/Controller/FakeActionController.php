<?php

namespace Test\Support\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webstract\Controller\ActionController;
use Webstract\Route\RoutePathTemplate;
use Test\Support\Route\FakeApiControllerRoute;

class FakeActionController extends ActionController
{
	public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$fakeRoute = new FakeApiControllerRoute();
		return $this->createRedirectResponse($fakeRoute->withPathParams(1234, 1234567));
	}

	public function testCreateRedirectResponse(RoutePathTemplate $route): ResponseInterface
	{
		return $this->createRedirectResponse($route);
	}
};
