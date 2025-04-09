<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

abstract class Controller
{
	public function __construct(
		protected readonly ResponseInterface $responseInterface,
		protected readonly StreamInterface $streamInterface,
	) {}

	public abstract function __invoke(ServerRequestInterface $serverRequest): ResponseInterface;
}
