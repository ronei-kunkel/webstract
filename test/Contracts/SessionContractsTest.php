<?php

declare(strict_types=1);

namespace Test\Contracts;

use Test\Support\Contracts\Session\FakeKeyValueSessionHandler;
use Test\Support\Contracts\Session\FakeSessionHandler;
use Test\Support\Contracts\Session\FakeSessionKey;
use Test\TestCase;
use Webstract\Session\Exception\SessionMissingException;
use Webstract\Session\Exception\SessionValueNotFoundException;

class SessionContractsTest extends TestCase
{
	public function test_ShouldSetGetAndConsumeValues_WhenSessionIsInitialized(): void
	{
		$key = new FakeSessionKey('identity');
		$handler = new FakeSessionHandler();
		$handler->initSession();
		$handler->set($key, 'abc');
		$this->assertTrue($handler->has($key));
		$this->assertSame('abc', $handler->get($key));
		$this->assertSame('abc', $handler->consume($key));
		$this->assertFalse($handler->has($key));
	}

	public function test_ShouldThrowException_WhenSessionValueIsMissing(): void
	{
		$handler = new FakeSessionHandler();
		$handler->initSession();
		$this->expectException(SessionValueNotFoundException::class);
		$handler->get(new FakeSessionKey('missing'));
	}

	public function test_ShouldEnforceSessionId_WhenUsingKeyValueSessionHandler(): void
	{
		$handler = new FakeKeyValueSessionHandler();
		$key = new FakeSessionKey('identity');
		$sessId = $handler->initSession();
		$handler->set($sessId, $key, 'value');
		$this->assertSame('value', $handler->consumeOrDefault($sessId, $key));
		$this->expectException(SessionMissingException::class);
		$handler->get('not-found', $key);
	}
}
