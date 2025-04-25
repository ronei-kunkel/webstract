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
use Webstract\Controller\Controller;
use Webstract\Controller\PageController;
use Webstract\Route\RouteResolver;
use Webstract\Session\SessionHandler;
use Webstract\TemplateEngine\TemplateEngineRenderer;

final class RequestHandler implements RequestHandlerInterface
{
	private readonly ResponseInterface $responseInterface;
	private readonly StreamInterface $streamInterface;

	public function __construct(
		private readonly RouteResolver $router,
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
		$routeDefinition = $this->router->resolve($serverRequest);

		$controllerInstance = $this->resolveControllerInstance($routeDefinition->getController());

		$pipeline = new RequestHandlerPipeline($controllerInstance);

		return $pipeline->handle($serverRequest);
	}

	private function resolveControllerInstance(string $controller): Controller
	{
		$reflectedController = new ReflectionClass($controller);

		return match (true) {
			$reflectedController->isSubclassOf(AsyncComponentController::class),
			$reflectedController->isSubclassOf(PageController::class) => $this->statefulControllerWithRender($controller),
			$reflectedController->isSubclassOf(ActionController::class) => $this->statefulController($controller),
			$reflectedController->isSubclassOf(ApiController::class),
			$reflectedController->isSubclassOf(Controller::class) => $this->statelessController($controller),
			default => throw new \RuntimeException("Cannot handle controller: {$controller}")
		};
	}

	private function statefulControllerWithRender(string $controller): object
	{
		$this->sessionHandler->initSession();

		return new $controller(
			$this->responseInterface,
			$this->streamInterface,
			$this->sessionHandler,
			$this->templateEngineRenderer
		);
	}

	private function statefulController(string $controller): object
	{
		$this->sessionHandler->initSession();

		return new $controller(
			$this->responseInterface,
			$this->streamInterface,
			$this->sessionHandler,
		);
	}

	private function statelessController(string $controller): object
	{
		return new $controller(
			$this->responseInterface,
			$this->streamInterface,
		);
	}
}
