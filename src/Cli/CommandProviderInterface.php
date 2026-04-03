<?php

declare(strict_types=1);

namespace Webstract\Cli;

interface CommandProviderInterface
{
	/**
	 * @throws \Exception
	 * @param array<string,class-string<Webstract\Cli\Command>> $commands
	 */
	public function __construct(
		array $commands = [],
	);

	/** @return ?class-string */
	public function resolveFromArgv1(string $arg): ?string;
}
