<?php

declare(strict_types=1);

namespace Test\Database;

use PHPUnit\Framework\Attributes\Group;
use Test\TestCase;
use Webstract\Database\DatabasePdoConnector;
use Webstract\Env\Visitor\DatabaseEnvironmentVarVisitor;

#[Group('infra')]
final class DatabasePdoConnectorTest extends TestCase
{
    private string $dsn;

    protected function setUp(): void
    {
        $this->dsn = 'sqlite:' . sys_get_temp_dir() . '/webstract-db-' . uniqid('', true) . '.sqlite';
        $this->resetConnectorConnection();
    }

    protected function tearDown(): void
    {
        $this->resetConnectorConnection();
        @unlink(str_replace('sqlite:', '', $this->dsn));
    }

    public function testItExecutesStatementsAndPersistsDataWithSqlite(): void
    {
        $connector = new DatabasePdoConnector($this->createDbEnv($this->dsn));

        $connector->exec('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL)');

        $statement = $connector->prepare('INSERT INTO users(name) VALUES (:name)');
        self::assertNotFalse($statement);
        $statement->execute(['name' => 'Alice']);

        self::assertSame('1', $connector->lastInsertId());

        $select = $connector->prepare('SELECT name FROM users WHERE id = :id');
        self::assertNotFalse($select);
        $select->execute(['id' => 1]);

        self::assertSame('Alice', $select->fetch()->name);
    }

    public function testItHandlesTransactionsAgainstRealConnection(): void
    {
        $connector = new DatabasePdoConnector($this->createDbEnv($this->dsn));
        $connector->exec('CREATE TABLE events (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL)');

        self::assertTrue($connector->beginTransaction());
        self::assertTrue($connector->inTransaction());

        $insert = $connector->prepare('INSERT INTO events(name) VALUES (:name)');
        self::assertNotFalse($insert);
        $insert->execute(['name' => 'rolled back']);

        self::assertTrue($connector->rollBack());
        self::assertFalse($connector->inTransaction());

        $count = $connector->prepare('SELECT COUNT(*) as total FROM events');
        self::assertNotFalse($count);
        $count->execute();
        self::assertSame(0, (int) $count->fetch()->total);
    }

    private function createDbEnv(string $dsn): DatabaseEnvironmentVarVisitor
    {
        return new class($dsn) implements DatabaseEnvironmentVarVisitor {
            public function __construct(private readonly string $dsn) {}
            public function getDatabaseDsn(): string { return $this->dsn; }
            public function getDatabaseHost(): string { return ''; }
            public function getDatabaseName(): string { return ''; }
            public function getDatabaseUser(): string { return ''; }
            public function getDatabaseType(): string { return ''; }
            public function getDatabasePort(): string { return ''; }
            public function getDatabasePassword(): string { return ''; }
        };
    }

    private function resetConnectorConnection(): void
    {
        $reflection = new \ReflectionClass(DatabasePdoConnector::class);
        $property = $reflection->getProperty('connection');
        $property->setAccessible(true);
        $property->setValue(null, null);
    }
}
