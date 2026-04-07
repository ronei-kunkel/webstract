<?php

declare(strict_types=1);

namespace Webstract\Cli\Commands;

use Webstract\Storage\FileHandler;
use Webstract\Cli\Command;
use Webstract\Env\Visitor\DatabaseEnvironmentVarVisitor;
use Psr\Log\LoggerInterface;

final class DatabaseRestore extends Command
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
	) {}

	public function preExecutionHook(): void
	{
		$this->host = $this->dbEnv->getDatabaseHost();
		$this->name = $this->dbEnv->getDatabaseName();
		$this->user = $this->dbEnv->getDatabaseUser();
		$this->pass = $this->dbEnv->getDatabasePassword();
		$this->filepath = "/app/db/backup/{$this->name}.backup.gz";

		$this->fileHandler->downloadDatabaseBackup($this->filepath);
	}

	public function command(): string
	{
		return <<<SH
		gunzip -c $this->filepath | PGPASSWORD=$this->pass pg_restore -h $this->host -U $this->user -d $this->name --clean --if-exists
		SH;
	}

	public function postExecutionHook(): void
	{
		unlink($this->filepath);
	}

	public function onFailedExecutionHook(): void
	{
		unlink($this->filepath);
	}
}
