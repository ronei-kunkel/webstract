<?php

declare(strict_types=1);

namespace Test\Support\RoneiKunkel\Webstract\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use RoneiKunkel\Webstract\Controller\AsyncComponentController;
use RoneiKunkel\Webstract\Web\AsyncComponent;
use Test\Support\RoneiKunkel\Webstract\Route\FakeRoute;
use Test\Support\RoneiKunkel\Webstract\Web\FakeAsyncComponent\FakeRenderableAsyncComponent;

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
		$route = (new FakeRoute())->withPathParams('123', 123);
		return $this->createRedirectResponse($route);
	}
}
