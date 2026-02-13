<?php

declare(strict_types=1);

namespace Webstract\Log\Collector;

use Webstract\Log\Collector\LogCollector;
use Monolog\Level;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class LogCollectorHandler extends AbstractProcessingHandler
{
	public function __construct(
		private readonly LogCollector $collector,
		$level = Level::Debug,
		bool $bubble = true
	) {
		parent::__construct($level, $bubble);
	}

	protected function write(LogRecord $record): void
	{
		$this->collector->add($record);
	}
}
