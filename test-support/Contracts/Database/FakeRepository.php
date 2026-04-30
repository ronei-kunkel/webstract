<?php

declare(strict_types=1);

namespace Test\Support\Contracts\Database;

use RuntimeException;
use Webstract\Database\DatabaseRepositoryConnector;
use Webstract\Database\Repository;

class FakeRepository implements Repository
{
	public function __construct(private readonly DatabaseRepositoryConnector $connector)
	{
	}

	public function connector(): DatabaseRepositoryConnector
	{
		return $this->connector;
	}

	public function run(callable $operation): mixed
	{
		$result = $operation();
		if ($result instanceof RuntimeException) {
			throw $result;
		}
		return $result;
	}
}
