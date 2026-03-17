<?php

declare(strict_types=1);

namespace Webstract\Env\Visitor;

interface LogEnvironmentVarVisitor
{
	public function getLogApiKey(): string;
}
