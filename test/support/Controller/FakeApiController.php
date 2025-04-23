<?php

namespace Test\Support\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webstract\Controller\ApiController;

class FakeApiController extends ApiController
{
	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
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
