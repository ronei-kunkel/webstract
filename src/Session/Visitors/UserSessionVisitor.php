<?php

declare(strict_types=1);

namespace Webstract\Session\Visitors;

use Exception;
use Webstract\Identity\LoggedUser;

interface UserSessionVisitor
{
	public function storeUser(LoggedUser $user): void;

	/** @throws Exception */
	public function retrieveUser(): LoggedUser;

	public function removeUser(): void;
}
