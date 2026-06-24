<?php

declare(strict_types=1);

namespace Test\Support\Contracts\Session;

use Webstract\Session\SessionKeyInterface;

class FakeSessionKey implements SessionKeyInterface
{
	public function __construct(private readonly string $name) {}
	public function getName(): string { return $this->name; }
}
