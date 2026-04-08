<?php

declare(strict_types=1);

namespace Webstract\Request;

use Psr\Container\ContainerInterface;
use Webstract\Env\Visitor\ApplicationEnvironmentVarVisitor;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Webstract\Request\RequestHandler;

final class SafeRequestHandler implements RequestHandlerInterface
{
	private readonly LoggerInterface $logger;
	private readonly ApplicationEnvironmentVarVisitor $appEnv;
	private readonly SafeRequestHandlerServerErrorControllerProvider $serverErrorControllerProvider;

	private ?\Closure $errorHandlerFunction = null;
	private ?\Closure $exceptionHandlerFunction = null;
	private ?\Closure $shutdownHandlerFunction = null;

	public function __construct(
		private readonly RequestHandler $requestHandler,
		private readonly ContainerInterface $container,
	) {
		$this->logger = $this->container->get(LoggerInterface::class);
		$this->appEnv = $this->container->get(ApplicationEnvironmentVarVisitor::class);
	}

	public function registerServerErrorControllerProvider(SafeRequestHandlerServerErrorControllerProvider $serverErrorControllerProvider): self
	{
		$this->serverErrorControllerProvider = $serverErrorControllerProvider;

		return $this;
	}

	public function registerErrorHandler(callable $errorHandlerFunction): self
	{
		$this->errorHandlerFunction = $errorHandlerFunction;

		return $this;
	}

	public function registerExceptionHandler(callable $exceptionHandlerFunction): self
	{
		$this->exceptionHandlerFunction = $exceptionHandlerFunction;

		return $this;
	}

	public function registerShutdownFunction(callable $shutdownHandlerFunction): self
	{
		$this->shutdownHandlerFunction = $shutdownHandlerFunction;

		return $this;
	}

	public function handle(ServerRequestInterface $serverRequest): ResponseInterface
	{
		if ($this->errorHandlerFunction) {
			$this->appEnv->isDevEnv() ?: set_error_handler($this->errorHandlerFunction);
		}

		if ($this->exceptionHandlerFunction) {
			$this->appEnv->isDevEnv() ?: set_exception_handler($this->exceptionHandlerFunction);
		}

		if ($this->shutdownHandlerFunction) {
			$this->appEnv->isDevEnv() ?: register_shutdown_function($this->shutdownHandlerFunction);
		}

		try {
			return $this->requestHandler->handle($serverRequest);
		} catch (\Throwable $th) {
			return $this->appEnv->isDevEnv()
				? throw $th
				: $this->handleException($th, $serverRequest);
		}
	}

	private function handleException(\Throwable $th, ServerRequestInterface $serverRequest): ResponseInterface
	{
		$this->logger->emergency($th->getMessage(), ['exception' => $th]);

		$accept = $serverRequest->getHeaderLine('Accept');

		$controllerClass = match (true) {
			str_contains($accept, 'application/json') => $this->serverErrorControllerProvider->getJsonServerErrorController(),
			str_contains($accept, 'text/html') => $this->serverErrorControllerProvider->getHtmlServerErrorController(),
			default => $this->serverErrorControllerProvider->getHtmlServerErrorController()
		};

		/** @var RequestHandlerInterface $controller */
		$controller = $this->container->get($controllerClass);

		return $controller->handle($serverRequest);
	}
}
