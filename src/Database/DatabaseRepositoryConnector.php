<?php

declare(strict_types=1);

namespace Webstract\Database;

use PDOStatement;

interface DatabaseRepositoryConnector
{
	public function prepare(string $query): PDOStatement|false;
	public function exec(string $statement): int|false;
	public function lastInsertId(): string|false;
}
