<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface Controller extends RequestHandlerInterface
{
	/** @return MiddlewareInterface[]*/
	public function middlewares(): array;

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface;
}
