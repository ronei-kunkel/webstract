<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

// @todo controller should have to register middlewares instead registered into routes
// @todo controller should have to register the expected input
abstract class Controller
{
	public function __construct(
		protected readonly ResponseInterface $responseInterface,
		protected readonly StreamInterface $streamInterface,
	) {}

	abstract public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface;
}
