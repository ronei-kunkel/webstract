<?php

declare(strict_types=1);

namespace Webstract\Database;

use Error;
use Webstract\Env\Visitor\DatabaseEnvironmentVarVisitor;
use PDO;
use PDOException;

abstract class SimplePdoConnection
{
	protected const int FETCH_MODE = PDO::FETCH_CLASS;
	protected const string FETCH_CLASS = 'stdClass';
	private static ?PDO $connection = null;

	public function __construct(
		private readonly DatabaseEnvironmentVarVisitor $dbEnv,
	) {
		self::$connection = $this->connect();
		self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		self::$connection->setAttribute(PDO::ATTR_PERSISTENT, true);
	}

	private function connect(): PDO
	{
		if (!self::$connection) {

			self::$connection = new PDO(
				$this->dbEnv->getDatabaseDsn(),
			);
		}

		return self::$connection;
	}

	protected function getConnection(): PDO
	{
		return self::$connection;
	}

	/**
	 * @throws Error - when cnnection is null
	 * @throws PDOException - when execute or fetchAll fails
	 */
	protected function fetchAllQueryResult(string $query, ?array $params = null): array
	{
		$stmt = $this->getConnection()->prepare($query);
		$stmt->execute($params);
		return $stmt->fetchAll(self::FETCH_MODE, self::FETCH_CLASS);
	}

	protected function insertWithLastIdResult(string $query, ?array $params = null): int
	{
		$this->getConnection()->prepare($query)->execute($params);
		return (int) $this->getConnection()->lastInsertId();
	}

	protected function fetchIdQueryResult(string $query, ?array $params = null): int
	{
		$stmt = $this->getConnection()->prepare($query);
		$stmt->execute($params);
		$result = $stmt->fetchObject();

		return $result
			? (int) $result->id
			: 0;
	}
}
