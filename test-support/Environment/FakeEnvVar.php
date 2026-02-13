<?php

declare(strict_types=1);

namespace Test\Support\Environment;

use Webstract\Env\EnvironmentVarInterface;

enum FakeEnvVar: string implements EnvironmentVarInterface
{
	case FOO = 'FOO';

	public function getName(): string
	{
		return $this->value;
	}
}
