<?php

declare(strict_types=1);

namespace Webstract\Session\Keys;

use Webstract\Session\SessionKeyInterface;

enum AuthSessionKey: string implements SessionKeyInterface
{
	case LOGGED_USER = 'AuthSessionKey.LOGGED_USER';

	public function getName(): string
	{
		return $this->value;
	}
}
