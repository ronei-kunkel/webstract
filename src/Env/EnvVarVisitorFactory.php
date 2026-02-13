<?php

declare(strict_types=1);

namespace Webstract\Env;

final class EnvVarVisitorFactory
{
	public static function createVisitor(): EnvVarVisitor
	{
		$envVarLoader = new EnvVarLoader();
		$envVarHandler = new EnvVarHandler($envVarLoader);
		return new EnvVarVisitor($envVarHandler);
	}
}
