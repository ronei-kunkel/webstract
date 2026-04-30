<?php

declare(strict_types=1);

namespace Test\Environment;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Test\Support\Environment\FakeEnvVar;
use Test\TestCase;
use Webstract\Env\EnvVarHandler;
use Webstract\Env\EnvironmentVarLoaderInterface;
use Webstract\Env\Exception\EnvironmentVarNotResolvedException;

#[CoversClass(EnvVarHandler::class)]
#[RunInSeparateProcess]
final class EnvVarHandlerTest extends TestCase
{
	public function test_getVar_ShouldReturnValueWhenEnvVarExists(): void
	{
		$loader = $this->createMock(EnvironmentVarLoaderInterface::class);
		$loader->expects($this->once())->method('load');

		$_ENV[FakeEnvVar::FOO->getName()] = 'bar';

		$handler = new EnvVarHandler($loader);

		$this->assertSame('bar', $handler->getVar(FakeEnvVar::FOO));
	}

	public function test_getVar_ShouldThrowWhenEnvVarDoesNotExist(): void
	{
		$loader = $this->createMock(EnvironmentVarLoaderInterface::class);
		$loader->expects($this->once())->method('load');

		unset($_ENV[FakeEnvVar::FOO->getName()]);
		putenv(FakeEnvVar::FOO->getName());

		$handler = new EnvVarHandler($loader);

		$this->expectException(EnvironmentVarNotResolvedException::class);
		$this->expectExceptionMessage('Environment variable `FOO` was not resolved.');

		$handler->getVar(FakeEnvVar::FOO);
	}

	public function test_getVarOrDefault_ShouldRespectNullAndNonNullDefaults(): void
	{
		$loader = $this->createMock(EnvironmentVarLoaderInterface::class);
		$loader->expects($this->once())->method('load');

		unset($_ENV[FakeEnvVar::FOO->getName()]);
		putenv(FakeEnvVar::FOO->getName());

		$handler = new EnvVarHandler($loader);

		$this->assertNull($handler->getVarOrDefault(FakeEnvVar::FOO, null));
		$this->assertSame('fallback', $handler->getVarOrDefault(FakeEnvVar::FOO, 'fallback'));
	}
}
