<?php

declare(strict_types=1);

namespace Test\Environment;

use Test\Support\Environment\FakeEnvVar;
use Test\TestCase;
use Webstract\Env\Exception\EnvironmentVarNotResolvedException;

final class EnvironmentExceptionTest extends TestCase
{
	public function test_EnvironmentVarNotResolvedException_ShouldReturnExpectedMessage(): void
	{
		$exception = new EnvironmentVarNotResolvedException(FakeEnvVar::FOO);

		$this->assertSame('Environment variable `FOO` was not resolved.', $exception->getMessage());
	}
}
