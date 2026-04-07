<?php

declare(strict_types=1);

namespace Webstract\Rbac;

interface PermissionProvider
{
	/** @return string[] */
	public function permissions(): array;
}
