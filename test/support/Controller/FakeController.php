<?php

namespace Test\Support\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Webstract\Controller\Controller;

class FakeController extends Controller
{
	public function __construct(
		private readonly ResponseInterface $response,
		private readonly StreamInterface $stream,
	) {}

	public function middlewares(): array
	{
		return [];
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$this->stream->write('Hello, World!');
		return $this->response->withBody($this->stream);
	}
}
