<?php

declare(strict_types=1);

namespace Test\Support\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Test\Support\Web\FakeComponent\FakeComponent;
use Test\Support\Web\FakeContent\FakeContent;
use Test\Support\Web\FakePage\FakePage;
use Webstract\Controller\PageController;

final class FakePageController extends PageController
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$content = new FakeContent(['a', 'b'], 'paragraph');
		$fakeComponent = new FakeComponent('Component text');
		$page = new FakePage(
			$content,
			$fakeComponent,
		);
		return $this->createHtmlResponse($page);
	}
}
