<?php

declare(strict_types=1);

namespace Webstract\Request;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webstract\Route\RouteResolver;

final class RequestHandler implements RequestHandlerInterface
{
	public function __construct(
		private readonly RouteResolver $router,
		private readonly ContainerInterface $container
	) {
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$routerOutput = $this->router->resolve($serverRequest);

		$pipeline = new RequestHandlerPipeline($routerOutput->route->getController(), $this->container);

		if (!$routerOutput->pathParams) {
			return $pipeline->handle($serverRequest);
		}

		foreach ($routerOutput->pathParams as $name => $value) {
			$serverRequest = $serverRequest->withAttribute($name, $value);
		}

		return $pipeline->handle($serverRequest);
	}
}
