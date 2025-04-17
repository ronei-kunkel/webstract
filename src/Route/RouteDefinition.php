<?php

declare(strict_types=1);

namespace Webstract\Route;

use Webstract\Request\RequestMethod;

interface RouteDefinition
{
	public function getMethod(): RequestMethod;

	public function getPattern(): string;

	/**
	 * @return class-string
	 */
	public function getController(): string;
}
