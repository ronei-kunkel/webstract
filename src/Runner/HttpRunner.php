<?php

declare(strict_types=1);

namespace Webstract\Runner;

use Webstract\Request\SafeRequestHandler;
use Webstract\Route\FastRouteRouter;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;
use Webstract\Request\RequestHandler;
use Webstract\Route\RouteProviderInterface;
use Webstract\Route\RouterOutputProvider;

final class HttpRunner extends Runner
{
	public function __construct(
		private readonly ServerRequestFactoryInterface $serverRequestFactory,
		private readonly UriFactoryInterface $uriFactory,
		private readonly UploadedFileFactoryInterface $uploadedFileFactory,
		private readonly StreamFactoryInterface $streamFactory,
		private readonly RouteProviderInterface $baseRouteProvider,
		private readonly RouteProviderInterface $customRouteProvider,
		private readonly RouterOutputProvider $routerOutputProvider,
	) {}

	public function execute(): void
	{
		$router = new FastRouteRouter(
			$this->customRouteProvider,
			$this->routerOutputProvider,
			$this->baseRouteProvider,
			);
		$requestHandler = new RequestHandler(
			$router,
			$this->container,
		);

		$requestHandlerDecorator = new SafeRequestHandler(
			$requestHandler,
			$this->container
		)->registerShutdownFunction(
			function () {
				$error = error_get_last();
				if ($error !== null) {
					$this->container->get(LoggerInterface::class)->emergency('Fatal error occurred', [
						'message' => $error['message'],
						'type' => $error['type'],
						'file' => $error['file'],
						'line' => $error['line'],
					]);
				}
			}
		)->registerErrorHandler(
			function ($severity, $message, $file, $line) {
				$this->container->get(LoggerInterface::class)->emergency($message, ['exception' => new \ErrorException($message, 0, $severity, $file, $line)]);
			}
		);
		$request = new ServerRequestCreator(
			$this->serverRequestFactory,
			$this->uriFactory,
			$this->uploadedFileFactory,
			$this->streamFactory,
		)->fromGlobals();

		$response = $requestHandlerDecorator->handle($request);
		new SapiEmitter()->emit($response);
	}
}
