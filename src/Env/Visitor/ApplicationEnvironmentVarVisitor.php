<?php

declare(strict_types=1);

namespace Webstract\Env\Visitor;

interface ApplicationEnvironmentVarVisitor
{
	public function getAppName(): string;
	public function isDevEnv(): bool;
}
