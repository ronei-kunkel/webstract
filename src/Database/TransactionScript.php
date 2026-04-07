<?php

declare(strict_types=1);

namespace Webstract\Database;

use Webstract\Database\TransactionScriptInterface;
use Webstract\Database\SimplePdoConnection;

final class TransactionScript extends SimplePdoConnection implements TransactionScriptInterface
{
	public function beginTransaction(): void
	{
		$this->getConnection()->beginTransaction();
	}

	public function commit(): void
	{
		$this->getConnection()->commit();
	}

	public function rollback(): void
	{
		$this->getConnection()->rollBack();
	}
}
