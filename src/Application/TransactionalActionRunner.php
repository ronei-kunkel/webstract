<?php

declare(strict_types=1);

namespace Webstract\Application;

use Webstract\Database\DatabaseTransactionManager;

final class TransactionalActionRunner
{
	public function __construct(
		private DatabaseTransactionManager $tx,
	) {}

	public function run(callable $fn): mixed
	{
		$this->tx->beginTransaction();

		try {
			$result = $fn();
			$this->tx->commit();
			return $result;
		} catch (\Throwable $e) {
			$this->tx->rollBack();
			throw $e;
		}
	}
}
