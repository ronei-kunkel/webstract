<?php

declare(strict_types=1);

namespace Webstract\Env;

interface EnvironmentVarInterface
{
	/** @return string */
	public function getName(): string;
}
