<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

// @todo controller should have to register the expected input
// @todo middlewares should be changed to array of MiddlewareInterface to class-string to be instantiate dynamically into pipeline
abstract class Controller implements RequestHandlerInterface
{
	public function __construct(
		protected readonly ResponseInterface $responseInterface,
		protected readonly StreamInterface $streamInterface,
	) {}

	/** @return MiddlewareInterface[]*/
	abstract public function middlewares(): array;

	abstract public function handle(ServerRequestInterface $serverRequest): ResponseInterface;
}
