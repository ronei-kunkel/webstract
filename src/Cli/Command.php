<?php

declare(strict_types=1);

namespace Webstract\Cli;

abstract class Command
{
	private array $args = [];

	public function injectArgs(array $args): void
	{
		$this->args = $args;
	}

	public function spreadArgs(): string
	{
		if (empty($this->args)) {
			return '';
		}

		return implode(' ', $this->args);
	}

	abstract public function preExecutionHook(): void;

	abstract public function command(): string;

	final public function execute(): void
	{
		exec($this->command());
	}

	abstract public function postExecutionHook(): void;
	abstract public function onFailedExecutionHook(): void;
}
