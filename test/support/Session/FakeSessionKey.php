<?php

namespace Test\Support\RoneiKunkel\Webstract\Session;

use RoneiKunkel\Webstract\Session\SessionKeyInterface;

enum FakeSessionKey: string implements SessionKeyInterface
{
	case TEST = 'test';
	case ANOTHER_TEST = 'another_test';

	public function getName(): string {
		return $this->value;
	}
}
