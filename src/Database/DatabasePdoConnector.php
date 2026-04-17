<?php

declare(strict_types=1);

namespace Webstract\Database;

use PDO;
use PDOStatement;
use Webstract\Env\Visitor\DatabaseEnvironmentVarVisitor;

final class DatabasePdoConnector implements DatabaseRepositoryConnector, DatabaseTransactionManager
{
	private static ?PDO $connection = null;

	public function __construct(
		private readonly DatabaseEnvironmentVarVisitor $dbEnv,
	) {}

	public function prepare(string $query): PDOStatement|false
	{
		return $this->getConnection()->prepare($query);
	}

	public function exec(string $statement): int|false
	{
		return $this->getConnection()->exec($statement);
	}

	public function lastInsertId(): string|false
	{
		return $this->getConnection()->lastInsertId();
	}

	public function inTransaction(): bool
	{
		return $this->getConnection()->inTransaction();
	}

	public function beginTransaction(): bool
	{
		return $this->getConnection()->beginTransaction();
	}

	public function commit(): bool
	{
		return $this->getConnection()->commit();
	}

	public function rollBack(): bool
	{
		return $this->getConnection()->rollBack();
	}

	private function getConnection(): PDO
	{
		if (!self::$connection) {
			self::$connection = new PDO(dsn: $this->dbEnv->getDatabaseDsn());
			self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		}

		return self::$connection;
	}
}
