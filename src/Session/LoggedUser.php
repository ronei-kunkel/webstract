<?php

declare(strict_types=1);

namespace Webstract\Session;

final class LoggedUser
{
	public function __construct(
		public readonly int $id,
		public readonly string $name,
		public readonly string $email,
	) {}
}
