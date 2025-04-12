<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Route;

interface RouteProvider
{
	/**
	 * @return RouteDefinition[]
	 */
	public function routes(): array;

	public function fallbackRoute(): RouteDefinition;
}
