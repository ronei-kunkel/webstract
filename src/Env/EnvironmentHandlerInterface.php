<?php

declare(strict_types=1);

namespace Webstract\Env;

use Webstract\Env\Exception\EnvironmentVarNotResolvedException;

interface EnvironmentHandlerInterface
{
	/** @return ?string */
	public function getVarOrDefault(EnvironmentVarInterface $envVar, ?string $defaultValue): ?string;

	/**
	 * @throws EnvironmentVarNotResolvedException
	 * @return string
	 */
	public function getVar(EnvironmentVarInterface $envVar): string;
}
