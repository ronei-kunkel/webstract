<?php

declare(strict_types=1);

namespace Test\Support\Contracts\Database;

use RuntimeException;
use Webstract\Database\DatabaseTransactionManager;

class FakeDatabaseTransactionManager implements DatabaseTransactionManager
{
	private bool $inTransaction = false;
	private bool $shouldFailOnBegin = false;
	private bool $shouldFailOnCommit = false;
	private bool $shouldFailOnRollback = false;

	public function failOnBegin(): void { $this->shouldFailOnBegin = true; }
	public function failOnCommit(): void { $this->shouldFailOnCommit = true; }
	public function failOnRollback(): void { $this->shouldFailOnRollback = true; }

	public function inTransaction(): bool { return $this->inTransaction; }
	public function beginTransaction(): bool
	{
		if ($this->shouldFailOnBegin) { throw new RuntimeException('begin failed'); }
		$this->inTransaction = true;
		return true;
	}
	public function commit(): bool
	{
		if ($this->shouldFailOnCommit) { throw new RuntimeException('commit failed'); }
		$this->inTransaction = false;
		return true;
	}
	public function rollBack(): bool
	{
		if ($this->shouldFailOnRollback) { throw new RuntimeException('rollback failed'); }
		$this->inTransaction = false;
		return true;
	}
}
