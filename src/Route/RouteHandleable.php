<?php

declare(strict_types=1);

namespace Webstract\Route;

interface RouteHandleable
{
	/** @return class-string */
	public static function getController(): string;
}
