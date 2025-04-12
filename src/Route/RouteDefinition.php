<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Route;

use RoneiKunkel\Webstract\Request\RequestMethod;

interface RouteDefinition
{
	public function getMethod(): RequestMethod;

	public function getPattern(): string;

	/**
	 * @return class-string
	 */
	public function getController(): string;
}
