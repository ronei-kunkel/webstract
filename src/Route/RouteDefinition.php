<?php

declare(strict_types=1);

namespace Webstract\Route;

use Webstract\Request\RequestMethod;

interface RouteDefinition extends RouteHandleable
{
	public static function getMethod(): RequestMethod;

	public static function getPattern(): string;
}
