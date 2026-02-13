<?php

declare(strict_types=1);

namespace Test\Support\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webstract\Controller\DownloadableActionController;

final class FakeDownloadableActionControllerThatCreate500ResponseCausedByUnableToOpenFile extends DownloadableActionController
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$filepath = __DIR__ . '/../assets/file-without-permissions.txt';

		exec('chmod 000 ' . $filepath);
		$response = $this->createDownloadResponse($filepath);
		exec('chmod 600 ' . $filepath);

		return $response;
	}
}
