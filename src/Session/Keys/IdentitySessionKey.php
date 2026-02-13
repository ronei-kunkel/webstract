<?php

declare(strict_types=1);

namespace Webstract\Session\Keys;

use Webstract\Session\SessionKeyInterface;

enum IdentitySessionKey: string implements SessionKeyInterface
{
	case ID = 'IdentitySessionKey.ID';
	case NAME = 'IdentitySessionKey.NAME';
	case EMAIL = 'IdentitySessionKey.EMAIL';
	case PERMISSIONS = 'IdentitySessionKey.PERMISSIONS';

	public function getName(): string
	{
		return $this->value;
	}
}
