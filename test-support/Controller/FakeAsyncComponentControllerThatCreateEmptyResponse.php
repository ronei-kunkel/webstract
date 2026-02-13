<?php

declare(strict_types=1);

namespace Test\Support\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webstract\Controller\AsyncComponentController;

final class FakeAsyncComponentControllerThatCreateEmptyResponse extends AsyncComponentController
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		return $this->createEmptyResponse();
	}
}
