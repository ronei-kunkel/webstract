<?php

declare(strict_types=1);

namespace Test\Support\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webstract\Controller\DownloadableActionController;

final class FakeDownloadableActionControllerWithDownloadableAttachment extends DownloadableActionController
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$filepath = __DIR__ . '/../assets/file-with-content.txt';
		return $this->createDownloadResponse($filepath);
	}
}
