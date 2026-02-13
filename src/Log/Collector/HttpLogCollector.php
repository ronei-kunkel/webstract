<?php

declare(strict_types=1);

namespace Webstract\Log\Collector;

use GuzzleHttp\Client;
use Monolog\LogRecord;

final class HttpLogCollector implements LogCollector
{
	private const int COLLECTOR_LIMIT = 10;

	private array $data;

	public function __construct(
		private readonly Client $client,
		private readonly string $endpoint,
		private readonly string $appName,
	) {
		$this->resetData();
	}

	private function resetData(): void
	{
		$this->data = [];
	}

	public function add(LogRecord $record): void
	{
		if (!count($this->data) === self::COLLECTOR_LIMIT) {
			$this->dispatchAsync();
			$this->resetData();
		}

		$this->data[] = [
			'timestamp' => $record['datetime']->format('Y-m-d\TH:i:s.uP'),
			'level' => $record['level_name'],
			'message' => $record['message'],
			'message_context' => $this->buildContext($record['context'])
		];
	}

	private function buildContext(array $context): array
	{
		return !array_key_exists('exception', $context)
			? $context
			: [
				'file' => $context['exception']->getFile(),
				'line' => $context['exception']->getLine(),
				'trace' => (object) $context['exception']->getTrace()[0],
			];
	}

	private function dispatchAsync(): void
	{
		$options = $this->buildRequestOptions();
		$this->client->postAsync($this->endpoint, $options);
	}

	public function flush(): void
	{
		if (php_sapi_name() !== 'cli' && function_exists('fastcgi_finish_request')) {
			fastcgi_finish_request();
		}

		$this->dispatchSync();
	}

	private function dispatchSync(): void
	{
		$this->client->post($this->endpoint, $this->buildRequestOptions());
	}

	private function buildRequestOptions(): array
	{
		return [
			'json' => [[
				'common' => [
					'attributes' => [
						'app' => $this->appName
					]
				],
				'logs' => $this->data,
			]],
		];
	}

	public function __destruct()
	{
		$this->flush();
	}
}
