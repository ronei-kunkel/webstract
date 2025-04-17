<?php

namespace Test\Support\Session;

use Webstract\Session\SessionKeyInterface;

enum FakeSessionKey: string implements SessionKeyInterface
{
	case TEST = 'test';
	case ANOTHER_TEST = 'another_test';

	public function getName(): string {
		return $this->value;
	}
}
