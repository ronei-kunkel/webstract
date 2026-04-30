<?php

declare(strict_types=1);

namespace Test\Environment;

use PHPUnit\Framework\Attributes\CoversClass;
use Test\TestCase;
use Webstract\Env\EnvVar;
use Webstract\Env\EnvVarVisitor;
use Webstract\Env\EnvironmentHandlerInterface;

#[CoversClass(EnvVarVisitor::class)]
final class EnvVarVisitorTest extends TestCase
{
	public function test_ApplicationDatabaseLogAndFileStorageVisitors_ShouldMapExpectedEnvironmentVars(): void
	{
		$environmentHandler = new class implements EnvironmentHandlerInterface {
			/** @var array<string, string> */
			private array $values = [
				'APP_NAME' => 'webstract',
				'ENVIRONMENT' => 'dev',
				'DB_TYPE' => 'pgsql',
				'DB_HOST' => 'database.local',
				'DB_PORT' => '5432',
				'DB_NAME' => 'webstract_db',
				'DB_USER' => 'db-user',
				'DB_PASS' => 'db-pass',
				'LOG_API_KEY' => 'log-key',
				'FILE_STORAGE_PUBLIC_API_KEY' => 'public-key',
				'FILE_STORAGE_SECRET_API_KEY' => 'secret-key',
				'FILE_STORAGE_BUCKET_REGION' => 'sa-saopaulo-1',
				'FILE_STORAGE_BUCKET_NAME' => 'bucket-name',
				'FILE_STORAGE_BUCKET_NAMESPACE' => 'namespace',
			];

			public function getVar(\Webstract\Env\EnvironmentVarInterface $envVar): string
			{
				return $this->values[$envVar->getName()];
			}

			public function getVarOrDefault(\Webstract\Env\EnvironmentVarInterface $envVar, ?string $defaultValue): ?string
			{
				return $this->values[$envVar->getName()] ?? $defaultValue;
			}
		};

		$visitor = new EnvVarVisitor($environmentHandler);

		$this->assertSame('webstract', $visitor->getAppName());
		$this->assertTrue($visitor->isDevEnv());
		$this->assertSame('database.local', $visitor->getDatabaseHost());
		$this->assertSame('webstract_db', $visitor->getDatabaseName());
		$this->assertSame('db-user', $visitor->getDatabaseUser());
		$this->assertSame('pgsql', $visitor->getDatabaseType());
		$this->assertSame('5432', $visitor->getDatabasePort());
		$this->assertSame('db-pass', $visitor->getDatabasePassword());
		$this->assertSame('pgsql:host=database.local;port=5432;dbname=webstract_db;user=db-user;password=db-pass', $visitor->getDatabaseDsn());

		$this->assertSame('log-key', $visitor->getLogApiKey());

		$this->assertSame('public-key', $visitor->getFileStoragePublicApiKey());
		$this->assertSame('secret-key', $visitor->getFileStorageSecretApiKey());
		$this->assertSame('sa-saopaulo-1', $visitor->getFileStorageBucketRegion());
		$this->assertSame('bucket-name', $visitor->getFileStorageBucketName());
		$this->assertSame('namespace', $visitor->getFileStorageBucketNamespace());
	}

	public function test_isDevEnv_ShouldDefaultToProdWhenEnvironmentVarIsMissing(): void
	{
		$environmentHandler = $this->createMock(EnvironmentHandlerInterface::class);
		$environmentHandler
			->expects($this->once())
			->method('getVarOrDefault')
			->with(EnvVar::ENVIRONMENT, 'prod')
			->willReturn('prod');

		$visitor = new EnvVarVisitor($environmentHandler);

		$this->assertFalse($visitor->isDevEnv());
	}
}
