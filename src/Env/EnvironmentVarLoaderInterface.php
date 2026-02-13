<?php

declare(strict_types=1);

namespace Webstract\Env;

interface EnvironmentVarLoaderInterface
{
	public function load(): void;
}
