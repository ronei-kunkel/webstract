<?php

declare(strict_types=1);

namespace Webstract\Runner;

use Webstract\Cli\CommandHandler;
use Webstract\Cli\CommandProvider;

final class CliRunner extends Runner
{
	public function __construct(
		private readonly CommandProvider $commandProvider,
	) {}

	public function execute(): void
	{
		new CommandHandler(
			$this->container,
			$this->commandProvider
		)->handle($_SERVER['argv']);
	}
}
