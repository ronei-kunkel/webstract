<?php

declare(strict_types=1);

namespace Webstract\Log\Collector;

use Monolog\LogRecord;

interface LogCollector
{
	public function add(LogRecord $record): void;

	public function flush(): void;
}
