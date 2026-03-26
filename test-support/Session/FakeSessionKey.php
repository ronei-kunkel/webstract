<?php

declare(strict_types=1);

namespace Test\Support\Session;

use Webstract\Session\SessionKeyInterface;

final class FakeSessionKey implements SessionKeyInterface
{
	public function __construct(private string $name)
	{
	}

	public function getName(): string
	{
		return $this->name;
	}
}
