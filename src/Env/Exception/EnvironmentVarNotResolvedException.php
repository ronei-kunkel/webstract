<?php

declare(strict_types=1);

namespace Webstract\Env\Exception;

use Webstract\Env\EnvironmentVarInterface;

final class EnvironmentVarNotResolvedException extends \Exception
{
	public function __construct(EnvironmentVarInterface $envVar)
	{
		parent::__construct("Environment variable `{$envVar->getName()}` was not resolved.");
	}
}
