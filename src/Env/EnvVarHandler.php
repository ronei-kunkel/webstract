<?php

declare(strict_types=1);

namespace Webstract\Env;

use Webstract\Env\EnvironmentHandlerInterface;
use Webstract\Env\EnvironmentVarInterface;
use Webstract\Env\EnvironmentVarLoaderInterface;
use Webstract\Env\Exception\EnvironmentVarNotResolvedException;

final class EnvVarHandler implements EnvironmentHandlerInterface
{
	public function __construct(
		private readonly EnvironmentVarLoaderInterface $loader,
	) {
		$this->loader->load();
	}

	/** @return ?string */
	public function getVarOrDefault(EnvironmentVarInterface $envVar, ?string $defaultValue): ?string
	{
		if (array_key_exists($envVar->getName(), $_ENV)) {
			return $_ENV[$envVar->getName()];
		}

		$value = getenv($envVar->getName());

		if (is_string($value)) {
			return $value;
		}

		return $defaultValue;
	}

	/**
	 * @throws EnvironmentVarNotResolvedException
	 * @return string
	 */
	public function getVar(EnvironmentVarInterface $envVar): string
	{
		$value = $this->getVarOrDefault($envVar, null);

		if ($value === null) {
			throw new EnvironmentVarNotResolvedException($envVar);
		}

		return $value;
	}
}
