<?php

declare(strict_types=1);

namespace Test\Support\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Test\Support\Web\FakeAsyncComponent\FakeRenderableAsyncComponent;
use Webstract\Controller\AsyncComponentController;

final class FakeAsyncComponentControllerThatCreateRenderedHtmlResponse extends AsyncComponentController
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$asyncComponent = new FakeRenderableAsyncComponent('RENDERABLE');
		return $this->createHtmlResponse($asyncComponent);
	}
}
