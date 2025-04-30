<?php

declare(strict_types=1);

namespace Webstract\Request;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webstract\Controller\Controller;

final class RequestHandlerPipeline implements RequestHandlerInterface
{
	private readonly Controller $controller;
	private array $middlewares;

	public function __construct(
		string $controller,
		private readonly ContainerInterface $container,
	) {
		$this->controller = $container->get($controller);
		$this->middlewares = $this->controller->middlewares();
	}

	/** @throws \RuntimeException */
	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		if (empty($this->middlewares)) {
			return $this->controller->handle($request);
		}

		$middleware = array_shift($this->middlewares);

		$middleware = $this->container->get($middleware);

		if (!$middleware instanceof MiddlewareInterface) {
			throw new \RuntimeException("Middleware " . get_class($middleware) . " does not implement Psr\Http\Server\MiddlewareInterface");
		}

		return $middleware->process($request, $this);
	}
}
