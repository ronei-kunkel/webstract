<?php

declare(strict_types=1);

namespace Webstract\Log;

use Webstract\Log\Collector\HttpLogCollector;
use Webstract\Log\Collector\LogCollectorHandler;
use Webstract\Env\Visitor\ApplicationEnvironmentVarVisitor;
use Webstract\Env\Visitor\LogEnvironmentVarVisitor;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Stringable;

final class NewRelicMonologLogger implements LoggerInterface
{
	private const string LOG_BASE_URL = 'https://log-api.newrelic.com';
	private const string LOG_ENDPOINT = '/log/v1';
	private const string DEV_ENV_LOG_DIR = '/app/.log/php/app.log';

	private LoggerInterface $logger;

	public function __construct(
		private readonly ApplicationEnvironmentVarVisitor $appEnv,
		private readonly LogEnvironmentVarVisitor $logEnv,
	) {
		$handlers = $this->appEnv->isDevEnv()
			? $this->buildDevEnvHandlers()
			: $this->buildProdEnvHandlers();

		$this->logger = new Logger('main', $handlers)->useMicrosecondTimestamps(true);
	}

	private function buildDevEnvHandlers(): array
	{
		return [
			new StreamHandler(self::DEV_ENV_LOG_DIR, Level::Debug)
		];
	}

	private function buildProdEnvHandlers(): array
	{
		$client = new Client([
			'base_uri' => self::LOG_BASE_URL,
			'timeout' => 2.0,
			'headers' => [
				'Api-Key' => $this->logEnv->getLogApiKey(),
				'Content-Type' => 'application/json',
			],
		]);

		$httpLogCollector = new HttpLogCollector(
			$client,
			self::LOG_ENDPOINT,
			$this->appEnv->getAppName(),
		);

		return [
			new LogCollectorHandler($httpLogCollector)
		];
	}

	public function emergency(string|Stringable $message, array $context = []): void
	{
		$this->logger->emergency($message, $context);
	}

	public function alert(string|Stringable $message, array $context = []): void
	{
		$this->logger->alert($message, $context);
	}

	public function critical(string|Stringable $message, array $context = []): void
	{
		$this->logger->critical($message, $context);
	}

	public function error(string|Stringable $message, array $context = []): void
	{
		$this->logger->error($message, $context);
	}

	public function warning(string|Stringable $message, array $context = []): void
	{
		$this->logger->warning($message, $context);
	}

	public function notice(string|Stringable $message, array $context = []): void
	{
		$this->logger->notice($message, $context);
	}

	public function info(string|Stringable $message, array $context = []): void
	{
		$this->logger->info($message, $context);
	}

	public function debug(string|Stringable $message, array $context = []): void
	{
		$this->logger->debug($message, $context);
	}

	public function log($level, string|Stringable $message, array $context = []): void
	{
		$this->logger->log($level, $message, $context);
	}
}
