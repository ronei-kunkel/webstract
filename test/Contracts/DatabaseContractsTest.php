<?php

declare(strict_types=1);

namespace Test\Contracts;

use RuntimeException;
use Test\Support\Contracts\Database\FakeDatabaseTransactionManager;
use Test\Support\Contracts\Database\FakeRepository;
use Test\TestCase;
use Webstract\Database\DatabaseRepositoryConnector;

class DatabaseContractsTest extends TestCase
{
	public function test_ShouldUpdateTransactionState_WhenBeginCommitAndRollback(): void
	{
		$manager = new FakeDatabaseTransactionManager();
		$this->assertFalse($manager->inTransaction());
		$this->assertTrue($manager->beginTransaction());
		$this->assertTrue($manager->inTransaction());
		$this->assertTrue($manager->commit());
		$this->assertFalse($manager->inTransaction());
		$manager->beginTransaction();
		$this->assertTrue($manager->rollBack());
		$this->assertFalse($manager->inTransaction());
	}

	public function test_ShouldThrowException_WhenTransactionOperationsFail(): void
	{
		$manager = new FakeDatabaseTransactionManager();
		$manager->failOnBegin();
		$this->expectException(RuntimeException::class);
		$manager->beginTransaction();
	}

	public function test_ShouldKeepProvidedConnector_WhenRepositoryIsConstructed(): void
	{
		$connector = $this->createStub(DatabaseRepositoryConnector::class);
		$repository = new FakeRepository($connector);
		$this->assertSame($connector, $repository->connector());
	}

	public function test_ShouldThrowException_WhenRepositoryOperationFails(): void
	{
		$repository = new FakeRepository($this->createStub(DatabaseRepositoryConnector::class));
		$this->expectException(RuntimeException::class);
		$repository->run(fn () => new RuntimeException('operation failed'));
	}
}
