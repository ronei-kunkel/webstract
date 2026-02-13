<?php

declare(strict_types=1);

namespace Webstract\Cli;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class CommandHandler
{
	private readonly LoggerInterface $logger;
	private readonly ?string $commandClass;

	public function __construct(
		private readonly ContainerInterface $container,
		private readonly CommandProviderInterface $commandProvider,
	) {
		$this->logger = $this->container->get(LoggerInterface::class);
	}

	public function handle(array $argv): void
	{
		unset($argv[0]);

		$argv1 = array_shift($argv);
		if (!$argv1) {
			$this->logger->error("No entry to handle.");
			return;
		}

		$this->commandClass = $this->commandProvider->resolveFromArgv1($argv1);
		if (!$this->commandClass) {
			$this->logger->error("Command {$argv1} in argv[1] was not resolved successfully.");
			return;
		}

		$this->logger->info("Command {$this->commandClass} will be started.");

		try {
			/** @var Command */
			$command = $this->container->get($this->commandClass);
			$this->executeCommand($command, $argv);
		} catch (\Throwable $th) {
			$this->logger->critical("Command {$this->commandClass} can't be solved.", [
				'exceptionMessage' => $th->getMessage(),
				'trace' => $th->getTrace(),
			]);
		}

		$this->logger->info("Command {$this->commandClass} was ended.");
	}

	private function executeCommand(Command $command, array $args): void
	{
		try {
			$command->injectArgs($args);
			$command->preExecutionHook();
			$command->execute();
			$command->postExecutionHook();
		} catch (\Throwable $th) {
			$command->onFailedExecutionHook();
			$this->logger->critical("Command {$this->commandClass} was failed.", [
				'exceptionMessage' => $th->getMessage(),
				'trace' => $th->getTrace(),
			]);
		}
	}
}
