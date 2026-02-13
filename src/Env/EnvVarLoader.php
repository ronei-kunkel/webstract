<?php

declare(strict_types=1);

namespace Webstract\Env;

use Dotenv\Dotenv;
use Webstract\Env\EnvironmentVarLoaderInterface;

final class EnvVarLoader implements EnvironmentVarLoaderInterface
{
	private const string ROOT_DIR = '/app';

	public function load(): void
	{
		Dotenv::createImmutable(self::ROOT_DIR)->safeLoad();
	}
}
