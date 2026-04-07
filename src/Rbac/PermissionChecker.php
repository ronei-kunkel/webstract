<?php

declare(strict_types=1);

namespace Webstract\Rbac;

use Webstract\Identity\LoggedUser;

final class PermissionChecker
{
	public function __construct(
		private PermissionRegistry $registry
	) {}

	public function can(LoggedUser $user, string $permission): bool
	{
		if (!in_array($permission, $this->registry->all(), true)) {
			throw new \InvalidArgumentException("Unknown permission");
		}

		return in_array($permission, $user->permissions, true);
	}
}
