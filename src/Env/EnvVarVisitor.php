<?php

declare(strict_types=1);

namespace Webstract\Env;

use Webstract\Env\EnvironmentHandlerInterface;
use Webstract\Env\Visitor\ApplicationEnvironmentVarVisitor;
use Webstract\Env\Visitor\DatabaseEnvironmentVarVisitor;
use Webstract\Env\Visitor\FileStorageEnvironmentVarVisitor;
use Webstract\Env\Visitor\LogEnvironmentVarVisitor;

final class EnvVarVisitor implements
	ApplicationEnvironmentVarVisitor,
	DatabaseEnvironmentVarVisitor,
	FileStorageEnvironmentVarVisitor,
	LogEnvironmentVarVisitor
{
	public function __construct(
		private readonly EnvironmentHandlerInterface $environmentHandler
	) {}

	public function getAppName(): string
	{
		return $this->environmentHandler->getVar(EnvVar::APP_NAME);
	}

	public function isDevEnv(): bool
	{
		$env = $this->environmentHandler->getVarOrDefault(EnvVar::ENVIRONMENT, 'prod');
		return $env !== 'prod';
	}

	public function getDatabaseDsn(): string
	{
		return sprintf(
			'%s:host=%s;port=%u;dbname=%s;user=%s;password=%s',
			$this->getDatabaseType(),
			$this->getDatabaseHost(),
			$this->getDatabasePort(),
			$this->getDatabaseName(),
			$this->getDatabaseUser(),
			$this->getDatabasePassword(),
		);
	}

	public function getDatabaseHost(): string
	{
		return $this->environmentHandler->getVar(EnvVar::DB_HOST);
	}

	public function getDatabaseName(): string
	{
		return $this->environmentHandler->getVar(EnvVar::DB_NAME);
	}

	public function getDatabaseUser(): string
	{
		return $this->environmentHandler->getVar(EnvVar::DB_USER);
	}

	public function getDatabaseType(): string
	{
		return $this->environmentHandler->getVar(EnvVar::DB_TYPE);
	}

	public function getDatabasePort(): string
	{
		return $this->environmentHandler->getVar(EnvVar::DB_PORT);
	}

	public function getDatabasePassword(): string
	{
		return $this->environmentHandler->getVar(EnvVar::DB_PASS);
	}

	public function getFileStoragePublicApiKey(): string
	{
		return $this->environmentHandler->getVar(EnvVar::FILE_STORAGE_PUBLIC_API_KEY);
	}

	public function getFileStorageSecretApiKey(): string
	{
		return $this->environmentHandler->getVar(EnvVar::FILE_STORAGE_SECRET_API_KEY);
	}

	public function getFileStorageBucketRegion(): string
	{
		return $this->environmentHandler->getVar(EnvVar::FILE_STORAGE_BUCKET_REGION);
	}

	public function getFileStorageBucketName(): string
	{
		return $this->environmentHandler->getVar(EnvVar::FILE_STORAGE_BUCKET_NAME);
	}

	public function getFileStorageBucketNamespace(): string
	{
		return $this->environmentHandler->getVar(EnvVar::FILE_STORAGE_BUCKET_NAMESPACE);
	}

	public function getFileStorageBucketBackupPrefixPreAuthKey(): string
	{
		return $this->environmentHandler->getVar(EnvVar::FILE_STORAGE_BUCKET_BACKUP_PREFIX_PRE_AUTH_KEY);
	}

	public function getLogApiKey(): string
	{
		return $this->environmentHandler->getVar(EnvVar::LOG_API_KEY);
	}
}
