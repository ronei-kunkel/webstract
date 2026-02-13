<?php

declare(strict_types=1);

namespace Webstract\Rbac;

final class PermissionRegistry
{
	/** @param PermissionProvider[] $providers */
	public function __construct(private array $providers) {}

	public function all(): array
	{
		return array_unique(array_merge(
			...array_map(fn($p) => $p->permissions(), $this->providers)
		));
	}
}
