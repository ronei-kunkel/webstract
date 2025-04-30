<?php

declare(strict_types=1);

namespace Test\Support\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webstract\Session\SessionHandler;

final class FakeFirstMiddleware implements MiddlewareInterface
{
	public function __construct(
		private readonly SessionHandler $session,
	) {}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$request = $request->withHeader('X-First-Middleware', 'first-middleware-value');
		return $handler->handle($request);
	}
}
