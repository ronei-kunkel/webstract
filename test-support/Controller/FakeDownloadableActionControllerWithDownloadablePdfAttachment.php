<?php

declare(strict_types=1);

namespace Test\Support\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Test\Support\Pdf\FakePdfContent;
use Webstract\Controller\DownloadableActionController;

final class FakeDownloadableActionControllerWithDownloadablePdfAttachment extends DownloadableActionController
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$pdfContent = new FakePdfContent('FakePdfContent');
		return $this->createPdfContentDownloadableResponse($pdfContent);
	}
}
