<?php

declare(strict_types=1);

namespace Webstract\Request;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionClass;
use Webstract\Controller\ActionController;
use Webstract\Controller\ApiController;
use Webstract\Controller\AsyncComponentController;
use Webstract\Controller\PageController;
use Webstract\Route\RouteProvider;
use Webstract\Route\RouteResolver;
use Webstract\Session\SessionHandler;
use Webstract\TemplateEngine\TemplateEngineRenderer;

final class RequestHandler implements RequestHandlerInterface
{
	private readonly ResponseInterface $responseInterface;
	private readonly StreamInterface $streamInterface;

	public function __construct(
		private readonly RouteProvider $routeProvider,
		private readonly SessionHandler $sessionHandler,
		private readonly TemplateEngineRenderer $templateEngineRenderer,
		ResponseFactoryInterface $responseFactory,
		StreamFactoryInterface $streamFactory,
	) {
		$this->responseInterface = $responseFactory->createResponse();
		$this->streamInterface   = $streamFactory->createStream();
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		$routeDefinition = new RouteResolver($this->routeProvider)->resolve($serverRequest);

		return $this->invokeController($routeDefinition->getController(), $serverRequest);
	}

	private function invokeController(string $controller, ServerRequestInterface $serverRequest): ResponseInterface
	{
		$controllerInstance = $this->resolveControllerInstance($controller);
		return $controllerInstance($serverRequest);
	}

	private function resolveControllerInstance(string $controller): object
	{
		$reflectedController = new ReflectionClass($controller);

		return match (true) {
			$reflectedController->isSubclassOf(AsyncComponentController::class),
			$reflectedController->isSubclassOf(PageController::class) => $this->instantiateRenderableStatefulController($controller),
			$reflectedController->isSubclassOf(ActionController::class) => $this->instantiateStatefulController($controller),
			$reflectedController->isSubclassOf(ApiController::class) => $this->instantiateStatelessController($controller),
			default => throw new \RuntimeException('Cannot handle controller with subclass: ' . $reflectedController->getParentClass()->getName())
		};
	}

	private function instantiateRenderableStatefulController(string $controller): object
	{
		$this->sessionHandler->create();

		return new $controller(
			$this->responseInterface,
			$this->streamInterface,
			$this->sessionHandler,
			$this->templateEngineRenderer
		);
	}

	private function instantiateStatefulController(string $controller): object
	{
		$this->sessionHandler->create();

		return new $controller(
			$this->responseInterface,
			$this->streamInterface,
			$this->sessionHandler,
		);
	}

	private function instantiateStatelessController(string $controller): object
	{
		return new $controller(
			$this->responseInterface,
			$this->streamInterface,
		);
	}
}
