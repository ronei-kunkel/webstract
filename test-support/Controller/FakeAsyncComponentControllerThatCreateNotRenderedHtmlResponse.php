<?php

declare(strict_types=1);

namespace Test\Support\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Test\Support\Web\FakeAsyncComponent\FakeNonRenderableAsyncComponent;
use Webstract\Controller\AsyncComponentController;

final class FakeAsyncComponentControllerThatCreateNotRenderedHtmlResponse extends AsyncComponentController
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$asyncComponent = new FakeNonRenderableAsyncComponent('NON RENDERABLE');
		return $this->createHtmlResponse($asyncComponent);
	}
}
