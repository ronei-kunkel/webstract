<?php

declare(strict_types=1);

namespace Webstract\Database;

interface TransactionScriptInterface
{
	public function beginTransaction(): void;

	public function commit(): void;

	public function rollback(): void;
}
