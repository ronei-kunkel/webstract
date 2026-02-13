<?php

declare(strict_types=1);

namespace Webstract\Cli;

use Webstract\Cli\Commands\DatabaseBackup;
use Webstract\Cli\Commands\DatabaseCreateMigration;
use Webstract\Cli\Commands\DatabaseMigrate;
use Webstract\Cli\Commands\DatabaseRestore;
use Webstract\Cli\Commands\DatabaseRollback;

final class CommandProvider implements CommandProviderInterface
{
	private const array FW_COMMANDS = [
		'database-create-migration' => DatabaseCreateMigration::class,

		'database-migrate' => DatabaseMigrate::class,
		'database-rollback' => DatabaseRollback::class,

		'database-backup' => DatabaseBackup::class,
		'database-restore' => DatabaseRestore::class,
	];

	/**
	 * @throws \Exception
	 * @param array<string,class-string<Webstract\Cli\Command>> $commands
	 */
	public function __construct(
		private array $commands = [],
	) {
		$this->throwIfOverrideCommands();
		$this->throwIfCommandIsNotInstanceOfCliCommand();

		$this->commands = array_merge(self::FW_COMMANDS, $this->commands);
	}

	/** @throws \Exception */
	private function throwIfOverrideCommands(): void
	{
		$newCommands = array_keys($this->commands);

		foreach ($newCommands as $newCommand) {
			$newCommandDontOverrideAnyReservedCommand = !array_key_exists($newCommand, self::FW_COMMANDS);
			if ($newCommandDontOverrideAnyReservedCommand) {
				continue;
			}

			throw new \Exception("Attempt to override reserved command `{$newCommand}` was detected. Choice another command to register `{$this->commands[$newCommand]}`.");
		}
	}

	/** @throws \Exception */
	private function throwIfCommandIsNotInstanceOfCliCommand(): void
	{
		$newCommands = array_keys($this->commands);

		foreach ($newCommands as $newCommand) {
			if ($newCommand instanceof Command) {
				continue;
			}

			throw new \Exception("Attempt to registry command `{$newCommand}` that dont implement expected interface.");
		}
	}

	/** @return ?class-string */
	public function resolveFromArgv1(string $arg): ?string
	{
		return $this->commands[$arg] ?? null;
	}
}
