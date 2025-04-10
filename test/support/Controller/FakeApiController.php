<?php

namespace Test\Support\RoneiKunkel\Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RoneiKunkel\Webstract\Common\Controller\DownloadableResponse;
use RoneiKunkel\Webstract\Controller\ApiController;

class FakeApiController extends ApiController
{
	use DownloadableResponse;

	public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
	{
		return $this->createJsonResponse(['message' => 'Hello, World!']);
	}

	public function testCreateJsonResponse($content): ResponseInterface
	{
		return $this->createJsonResponse($content);
	}

	public function testCreateDownloadResponse(string $filePath): ResponseInterface
	{
		return $this->createDownloadResponse($filePath);
	}
};
