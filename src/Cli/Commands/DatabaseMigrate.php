<?php

declare(strict_types=1);

namespace Webstract\Cli\Commands;

use Webstract\Cli\Command;

final class DatabaseMigrate extends Command
{
	public function preExecutionHook(): void {}

	public function command(): string
	{
		return <<<SH
		php /app/vendor/bin/phinx migrate -e default -c /app/db/phinx.php
		SH;
	}

	public function postExecutionHook(): void {}

	public function onFailedExecutionHook(): void {}
}
