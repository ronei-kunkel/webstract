<?php

declare(strict_types=1);

namespace Test\Cli;

use Test\TestCase;
use Webstract\Cli\Command;
use Webstract\Cli\CommandProvider;

final class CommandProviderTest extends TestCase
{
	public function test_ShouldRegisterValidCommand(): void
	{
		$provider = new CommandProvider([
			'custom-command' => FakeCommand::class,
		]);

		$this->assertSame(FakeCommand::class, $provider->resolveFromArgv1('custom-command'));
	}

	public function test_ShouldThrowWhenRegisteringTypeThatDoesNotImplementExpectedContract(): void
	{
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Attempt to registry command `invalid-command` that dont implement expected interface.');

		new CommandProvider([
			'invalid-command' => \stdClass::class,
		]);
	}

	public function test_ShouldResolveExistingCommand(): void
	{
		$provider = new CommandProvider();

		$this->assertSame(
			\Webstract\Cli\Commands\DatabaseMigrate::class,
			$provider->resolveFromArgv1('database-migrate')
		);
	}

	public function test_ShouldReturnNullWhenResolvingNonExistingCommand(): void
	{
		$provider = new CommandProvider();

		$this->assertNull($provider->resolveFromArgv1('unknown-command'));
	}

	public function test_ShouldKeepRegisteredCommandsOrderAndConsistency(): void
	{
		$provider = new CommandProvider([
			'custom-first' => FakeCommand::class,
			'custom-second' => FakeOtherCommand::class,
		]);

		$commands = $this->extractCommands($provider);

		$this->assertSame([
			'database-create-migration',
			'database-migrate',
			'database-rollback',
			'database-backup',
			'database-restore',
			'custom-first',
			'custom-second',
		], array_keys($commands));

		$this->assertSame(FakeCommand::class, $commands['custom-first']);
		$this->assertSame(FakeOtherCommand::class, $commands['custom-second']);
	}

	/** @return array<string, class-string<Command>> */
	private function extractCommands(CommandProvider $provider): array
	{
		$reflection = new \ReflectionClass($provider);
		$property = $reflection->getProperty('commands');
		$property->setAccessible(true);

		/** @var array<string, class-string<Command>> $commands */
		$commands = $property->getValue($provider);

		return $commands;
	}
}

final class FakeCommand extends Command
{
	public function preExecutionHook(): void {}
	public function command(): string
	{
		return 'echo fake';
	}
	public function postExecutionHook(): void {}
	public function onFailedExecutionHook(): void {}
}

final class FakeOtherCommand extends Command
{
	public function preExecutionHook(): void {}
	public function command(): string
	{
		return 'echo other';
	}
	public function postExecutionHook(): void {}
	public function onFailedExecutionHook(): void {}
}
