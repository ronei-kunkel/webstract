<?php

declare(strict_types=1);

namespace Webstract\Env\Visitor;

interface DatabaseEnvironmentVarVisitor
{
	public function getDatabaseDsn(): string;
	public function getDatabaseHost(): string;
	public function getDatabaseName(): string;
	public function getDatabaseUser(): string;
	public function getDatabaseType(): string;
	public function getDatabasePort(): string;
	public function getDatabasePassword(): string;
}
