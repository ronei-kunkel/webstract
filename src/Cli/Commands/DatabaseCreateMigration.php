<?php

declare(strict_types=1);

namespace Webstract\Cli\Commands;

use Webstract\Cli\Command;

final class DatabaseCreateMigration extends Command
{
	public function preExecutionHook(): void {}

	public function command(): string
	{
		return <<<SH
		php /app/vendor/bin/phinx create {$this->spreadArgs()} -c /app/db/phinx.php && chmod 777 -R /app/db/migrations
		SH;
	}

	public function postExecutionHook(): void {}

	public function onFailedExecutionHook(): void {}
}
