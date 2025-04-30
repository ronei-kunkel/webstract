<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class Controller implements RequestHandlerInterface
{
	/** @return MiddlewareInterface[]*/
	abstract public function middlewares(): array;

	abstract public function handle(ServerRequestInterface $serverRequest): ResponseInterface;
}
