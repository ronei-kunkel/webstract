<?php

declare(strict_types=1);

namespace Webstract\Cli\Commands;

use Webstract\Storage\FileHandler;
use Webstract\Cli\Command;
use Webstract\Env\Visitor\DatabaseEnvironmentVarVisitor;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

final class DatabaseBackup extends Command
{
	private string $host;
	private string $name;
	private string $user;
	private string $pass;
	private string $filepath;

	public function __construct(
		private readonly DatabaseEnvironmentVarVisitor $dbEnv,
		private readonly LoggerInterface $logger,
		private readonly FileHandler $fileHandler,
		private readonly StreamFactoryInterface $streamFactory,
	) {}

	public function preExecutionHook(): void
	{
		$this->host = $this->dbEnv->getDatabaseHost();
		$this->name = $this->dbEnv->getDatabaseName();
		$this->user = $this->dbEnv->getDatabaseUser();
		$this->pass = $this->dbEnv->getDatabasePassword();
		$this->filepath = "/app/db/backup/{$this->name}.backup.gz";
	}

	public function command(): string
	{
		return <<<SH
		PGPASSWORD=$this->pass pg_dump -h $this->host -U $this->user -d $this->name --clean -Fc | gzip > $this->filepath
		SH;
	}

	public function postExecutionHook(): void
	{
		$stream = $this->streamFactory->createStreamFromFile($this->filepath, 'r');
		$this->fileHandler->uploadDatabaseBackup($stream);
		unlink($this->filepath);
	}

	public function onFailedExecutionHook(): void {}
}
