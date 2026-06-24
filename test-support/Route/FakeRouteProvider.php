<?php

declare(strict_types=1);

namespace Test\Support\Route;

use Webstract\Route\RouteDefinition;
use Webstract\Route\RouteProviderInterface;

final class FakeRouteProvider implements RouteProviderInterface
{
	/** @param RouteDefinition[] $definitions */
	public function __construct(private readonly array $definitions)
	{
	}

	public function provideRouteDefinitions(): array
	{
		return $this->definitions;
	}
}
