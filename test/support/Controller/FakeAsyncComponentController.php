<?php

declare(strict_types=1);

namespace Test\Support\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webstract\Controller\AsyncComponentController;
use Webstract\Web\AsyncComponent;
use Test\Support\Route\FakeApiControllerRoute;
use Test\Support\Web\FakeAsyncComponent\FakeRenderableAsyncComponent;

final class FakeAsyncComponentController extends AsyncComponentController
{
	public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$component = new FakeRenderableAsyncComponent('FAKEE');
		return $this->createHtmlResponse($component);
	}

	public function testCreateHtmlResponse(AsyncComponent $component): ResponseInterface
	{
		return $this->createHtmlResponse($component);
	}

	public function testCreateEmptyResponse(): ResponseInterface
	{
		return $this->createEmptyResponse();
	}

	public function testCreateRedirectResponse(): ResponseInterface
	{
		$route = (new FakeApiControllerRoute())->withPathParams('123', 123);
		return $this->createRedirectResponse($route);
	}
}
