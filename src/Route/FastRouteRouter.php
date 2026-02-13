<?php

declare(strict_types=1);

namespace Webstract\Route;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Webstract\Route\Exception\RoutePatternOverrideException;
use Webstract\Route\RouteProviderInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webstract\Route\RouteResolver;
use Webstract\Route\RouterOutput;

use function FastRoute\simpleDispatcher;

final class FastRouteRouter implements RouteResolver
{
	/** @var string[] */
	private array $fwRouteDefinitionPatterns;

	/** @var \Webstract\Route\RouteDefinition[] */
	private readonly array $customRouteDefinitions;

	public function __construct(
		RouteProviderInterface $customRouteProvider,
		private readonly RouterOutputProvider $routerOutputProvider,
		private readonly RouteProviderInterface $baseRouteProvider,
	) {
		$this->customRouteDefinitions = $customRouteProvider->provideRouteDefinitions();
	}

	public function resolve(ServerRequestInterface $serverRequest): RouterOutput
	{
		$dispatcher = simpleDispatcher($this->registry(...));

		$routeInfo = $dispatcher->dispatch($serverRequest->getMethod(), $serverRequest->getUri()->getPath());

		return match ($routeInfo[0]) {
			Dispatcher::NOT_FOUND => $this->routerOutputProvider->routeNotFound(),
			Dispatcher::METHOD_NOT_ALLOWED => $this->routerOutputProvider->methodNotAllowed(),
			Dispatcher::FOUND => $this->routerOutputProvider->resolved($routeInfo[1], $routeInfo[2]),
		};
	}

	private function registry(RouteCollector $c): void
	{
		foreach ($this->baseRouteProvider->provideRouteDefinitions() as $fwRouteDefinition) {
			$c->addRoute($fwRouteDefinition::getMethod()->value, $fwRouteDefinition::getPattern(), $fwRouteDefinition::getController());
			$this->fwRouteDefinitionPatterns[] = $fwRouteDefinition::getPattern();
		}

		foreach ($this->customRouteDefinitions as $routeDefinition) {
			$routePattern = $routeDefinition::getPattern();
			if(in_array($routePattern, $this->fwRouteDefinitionPatterns)) {
				throw new RoutePatternOverrideException("The route definition `{$routeDefinition}` has pattern `{$routePattern}` that is reserved by framework.");
			}
			$c->addRoute($routeDefinition::getMethod()->value, $routePattern, $routeDefinition::getController());
		}
	}
}
