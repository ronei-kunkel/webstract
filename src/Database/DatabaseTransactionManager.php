<?php

declare(strict_types=1);

namespace Webstract\Database;

interface DatabaseTransactionManager
{
	public function inTransaction(): bool;
	public function beginTransaction(): bool;
	public function commit(): bool;
	public function rollBack(): bool;
}
