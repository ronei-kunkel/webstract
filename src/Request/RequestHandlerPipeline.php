<?php

declare(strict_types=1);

namespace Webstract\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webstract\Controller\Controller;

final class RequestHandlerPipeline implements RequestHandlerInterface
{
	private array $middlewares;

	public function __construct(
		private readonly Controller $controller
	) {
		$this->middlewares = $controller->middlewares();
	}

	/** @throws \RuntimeException */
	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		if (empty($this->middlewares)) {
			return $this->controller->handle($request);
		}

		$middleware = array_shift($this->middlewares);

		// @todo here should be instantiate the middleware instead instantiate into middlewares method inside controller

		if (!$middleware instanceof MiddlewareInterface) {
			throw new \RuntimeException("Middleware " . get_class($middleware) . " does not implement Psr\Http\Server\MiddlewareInterface");
		}

		return $middleware->process($request, $this);
	}
}
