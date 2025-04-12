<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Route;

interface RoutePathTemplate
{
	public function withPathParams(...$params): self;

	public function getPathFormat(): string;

	/**
	 * @throws \RuntimeException
	 * @return string
	 */
	public function renderPath(): string;
}
